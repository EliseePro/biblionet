<?php

require_once __DIR__ . '/../../config/config.php';

class Emprunt
{
    private static function requete(string $sql, array $params = []): PDOStatement
    {
        $stmt = connexion()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    private static function selectEmprunts(string $condition = '', array $params = []): array
    {
        $sql = 'SELECT e.*, a.nom AS adherent_nom, a.prenom AS adherent_prenom,
                       a.classe AS adherent_classe, a.telephone AS adherent_telephone,
                       l.titre AS livre_titre
                FROM emprunts e
                JOIN adherents a ON e.adherent_id = a.id
                JOIN livres l ON e.livre_id = l.id';
        if ($condition !== '') {
            $sql .= ' WHERE ' . $condition;
        }
        $sql .= ' ORDER BY e.date_emprunt DESC, e.id DESC';
        return self::requete($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function tous(): array
    {
        return self::selectEmprunts();
    }

    public static function trouver(int $id): ?array
    {
        return self::requete(
            'SELECT e.*, a.nom AS adherent_nom, a.prenom AS adherent_prenom,
                    a.classe AS adherent_classe, a.telephone AS adherent_telephone,
                    l.titre AS livre_titre
             FROM emprunts e
             JOIN adherents a ON e.adherent_id = a.id
             JOIN livres l ON e.livre_id = l.id
             WHERE e.id = ?',
            [$id]
        )->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function livreDisponible(int $livreId, ?int $empruntIgnore = null): bool
    {
        $exemplaires = self::requete(
            'SELECT nb_exemplaires FROM livres WHERE id = ?',
            [$livreId]
        )->fetchColumn();
        if ($exemplaires === false) {
            return false;
        }

        $sql = 'SELECT COUNT(*) FROM emprunts e
                WHERE e.livre_id = ? AND e.date_retour IS NULL';
        $params = [$livreId];
        if ($empruntIgnore !== null) {
            $sql .= ' AND e.id <> ?';
            $params[] = $empruntIgnore;
        }
        $empruntsActifs = self::requete($sql, $params)->fetchColumn();
        return (int) $empruntsActifs < (int) $exemplaires;
    }

    private static function reserverExemplaire(
        PDO $pdo,
        int $livreId,
        ?int $empruntIgnore = null,
        bool $verifierDisponibilite = true
    ): bool
    {
        $stmt = $pdo->prepare('SELECT nb_exemplaires FROM livres WHERE id = ? FOR UPDATE');
        $stmt->execute([$livreId]);
        $exemplaires = $stmt->fetchColumn();
        if ($exemplaires === false) {
            return false;
        }
        if (!$verifierDisponibilite) {
            return true;
        }

        $sql = 'SELECT COUNT(*) FROM emprunts WHERE livre_id = ? AND date_retour IS NULL';
        $params = [$livreId];
        if ($empruntIgnore !== null) {
            $sql .= ' AND id <> ?';
            $params[] = $empruntIgnore;
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn() < (int) $exemplaires;
    }

    public static function creer(array $donnees): bool
    {
        $pdo = connexion();
        try {
            $pdo->beginTransaction();
            if (!self::reserverExemplaire($pdo, (int) $donnees['livre_id'])) {
                $pdo->rollBack();
                return false;
            }

            $stmt = $pdo->prepare(
                'INSERT INTO emprunts (adherent_id, livre_id, date_emprunt) VALUES (?, ?, ?)'
            );
            $stmt->execute([
                (int) $donnees['adherent_id'],
                (int) $donnees['livre_id'],
                $donnees['date_emprunt'],
            ]);
            $pdo->commit();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            if ((string) $e->getCode() === '23000') {
                return false;
            }
            throw $e;
        }
    }

    public static function modifier(int $id, array $donnees): bool
    {
        $pdo = connexion();
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare('SELECT date_retour FROM emprunts WHERE id = ? FOR UPDATE');
            $stmt->execute([$id]);
            $emprunt = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($emprunt === false) {
                $pdo->rollBack();
                return false;
            }

            if (!self::reserverExemplaire(
                $pdo,
                (int) $donnees['livre_id'],
                $id,
                $emprunt['date_retour'] === null
            )) {
                $pdo->rollBack();
                return false;
            }

            $stmt = $pdo->prepare(
                'UPDATE emprunts SET adherent_id = ?, livre_id = ?, date_emprunt = ? WHERE id = ?'
            );
            $stmt->execute([
                (int) $donnees['adherent_id'],
                (int) $donnees['livre_id'],
                $donnees['date_emprunt'],
                $id,
            ]);
            $pdo->commit();
            return self::trouver($id) !== null;
        } catch (PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            if ((string) $e->getCode() === '23000') {
                return false;
            }
            throw $e;
        }
    }

    public static function supprimer(int $id): bool
    {
        return self::requete('DELETE FROM emprunts WHERE id = ?', [$id])->rowCount() > 0;
    }

    public static function marquerCommeRetourne(int $id): bool
    {
        return self::requete(
            'UPDATE emprunts SET date_retour = CURDATE() WHERE id = ? AND date_retour IS NULL',
            [$id]
        )->rowCount() > 0;
    }

    public static function empruntsEnCours(): array
    {
        return self::selectEmprunts('e.date_retour IS NULL');
    }

    public static function empruntsParAdherent(int $adherentId): array
    {
        return self::requete(
            'SELECT e.*, l.titre AS livre_titre
             FROM emprunts e JOIN livres l ON e.livre_id = l.id
             WHERE e.adherent_id = ? ORDER BY e.date_emprunt DESC, e.id DESC',
            [$adherentId]
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function empruntsParLivre(int $livreId): array
    {
        return self::requete(
            'SELECT e.*, a.nom AS adherent_nom, a.prenom AS adherent_prenom
             FROM emprunts e JOIN adherents a ON e.adherent_id = a.id
             WHERE e.livre_id = ? ORDER BY e.date_emprunt DESC, e.id DESC',
            [$livreId]
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function empruntsEnRetard(): array
    {
        return self::selectEmprunts(
            'e.date_retour IS NULL AND e.date_emprunt < DATE_SUB(CURDATE(), INTERVAL 14 DAY)'
        );
    }
}
