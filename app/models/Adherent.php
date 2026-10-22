<?php

require_once __DIR__ . '/../../config/config.php';

class Adherent
{
    private static function requete(string $sql, array $params = []): PDOStatement
    {
        $stmt = connexion()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function tous(): array
    {
        return self::requete('SELECT * FROM adherents ORDER BY nom, prenom, id')
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function pagines(int $limite, int $offset): array
    {
        $limite = max(1, min($limite, 100));
        $offset = max(0, $offset);
        return self::requete(
            'SELECT * FROM adherents ORDER BY nom, prenom, id LIMIT ' . $limite . ' OFFSET ' . $offset
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function compter(): int
    {
        return (int) self::requete('SELECT COUNT(*) FROM adherents')->fetchColumn();
    }

    public static function trouver(int $id): ?array
    {
        return self::requete('SELECT * FROM adherents WHERE id = ?', [$id])
            ->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function creer(array $donnees): bool
    {
        return self::requete(
            'INSERT INTO adherents (nom, prenom, classe, telephone) VALUES (?, ?, ?, ?)',
            [
                $donnees['nom'],
                $donnees['prenom'],
                $donnees['classe'],
                $donnees['telephone'] === '' ? null : $donnees['telephone'],
            ]
        )->rowCount() > 0;
    }

    public static function modifier(int $id, array $donnees): bool
    {
        self::requete(
            'UPDATE adherents SET nom = ?, prenom = ?, classe = ?, telephone = ? WHERE id = ?',
            [
                $donnees['nom'],
                $donnees['prenom'],
                $donnees['classe'],
                $donnees['telephone'] === '' ? null : $donnees['telephone'],
                $id,
            ]
        );
        return self::trouver($id) !== null;
    }

    public static function supprimer(int $id): bool
    {
        try {
            return self::requete('DELETE FROM adherents WHERE id = ?', [$id])->rowCount() > 0;
        } catch (PDOException $e) {
            if ((string) $e->getCode() === '23000') {
                return false;
            }
            throw $e;
        }
    }
}
