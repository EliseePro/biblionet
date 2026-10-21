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
public static function tous(): array
    {
        $sql = 'SELECT e.*, a.nom AS adherent_nom, l.titre AS livre_titre FROM emprunts e
                JOIN adherents a ON e.adherent_id = a.id
                JOIN livres l ON e.livre_id = l.id ORDER BY e.date_emprunt DESC';
        return self::requete($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

public static function trouver(int $id): ?array
    {
        $sql = 'SELECT e.*, a.nom AS adherent_nom, l.titre AS livre_titre FROM emprunts e
                JOIN adherents a ON e.adherent_id = a.id
                JOIN livres l ON e.livre_id = l.id WHERE e.id = ?';
        return self::requete($sql, [$id])->fetch(PDO::FETCH_ASSOC) ?: null;
    }

public static function creer(array $d): bool
    {
        $sql = 'INSERT INTO emprunts (adherent_id, livre_id, date_emprunt) VALUES (?, ?, ?)';
        return self::requete($sql, [$d['adherent_id'], $d['livre_id'], $d['date_emprunt']])->rowCount() > 0;
    }

public static function modifier(int $id, array $d): bool
    {
        $sql = 'UPDATE emprunts SET adherent_id = ?, livre_id = ?, date_emprunt = ?, date_retour = ? WHERE id = ?';
        self::requete($sql, [$d['adherent_id'], $d['livre_id'], $d['date_emprunt'], $d['date_retour'], $id]);
        return true;
    }

public static function supprimer(int $id): bool
    {
        try {
            return self::requete('DELETE FROM emprunts WHERE id = ?', [$id])->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }
public static function marquerCommeRetourne(int $id): bool
    {
        $sql = 'UPDATE emprunts SET date_retour = ? WHERE id = ?';
        self::requete($sql, [date('Y-m-d H:i:s'), $id]);
        return true;
    }

public static function empruntsEnCours(): array
    {
        $sql = 'SELECT e.*, a.nom AS adherent_nom, l.titre AS livre_titre FROM emprunts e
                JOIN adherents a ON e.adherent_id = a.id
                JOIN livres l ON e.livre_id = l.id WHERE e.date_retour IS NULL ORDER BY e.date_emprunt DESC';
        return self::requete($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

public static function empruntsParAdherent(int $adherentId): array
    {
        $sql = 'SELECT e.*, l.titre AS livre_titre FROM emprunts e
                JOIN livres l ON e.livre_id = l.id WHERE e.adherent_id = ? ORDER BY e.date_emprunt DESC';
        return self::requete($sql, [$adherentId])->fetchAll(PDO::FETCH_ASSOC);
    }

public static function empruntsParLivre(int $livreId): array
    {
        $sql = 'SELECT e.*, a.nom AS adherent_nom FROM emprunts e
                JOIN adherents a ON e.adherent_id = a.id WHERE e.livre_id = ? ORDER BY e.date_emprunt DESC';
        return self::requete($sql, [$livreId])->fetchAll(PDO::FETCH_ASSOC);
    }

public static function empruntsEnRetard(): array
    {
        $sql = 'SELECT e.*, a.nom AS adherent_nom, l.titre AS livre_titre FROM emprunts e
                JOIN adherents a ON e.adherent_id = a.id
                JOIN livres l ON e.livre_id = l.id WHERE e.date_retour IS NULL AND e.date_emprunt < DATE_SUB(NOW(), INTERVAL 30 DAY) ORDER BY e.date_emprunt DESC';
        return self::requete($sql)->fetchAll(PDO::FETCH_ASSOC);
    }


}