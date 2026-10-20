<?php

if (!function_exists('connexion')) {
    require_once __DIR__ . '/../../config/config.php';
}

class Livre
{
    private const DISPONIBLES_SQL = '(l.nb_exemplaires - (SELECT COUNT(*) FROM emprunts e WHERE e.livre_id = l.id AND e.date_retour IS NULL))';

    private static ?PDO $bdd = null;

    private static function db(): PDO
    {
        if (self::$bdd === null) {
            self::$bdd = connexion();
            self::$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
        return self::$bdd;
    }

    public static function all(): array
    {
        $sql = 'SELECT l.*, ' . self::DISPONIBLES_SQL . ' AS disponibles
                FROM livres l
                ORDER BY l.titre';
        return self::db()->query($sql)->fetchAll();
    }

    public static function search(string $terme): array
    {
        $sql = 'SELECT l.*, ' . self::DISPONIBLES_SQL . ' AS disponibles
                FROM livres l
                WHERE l.titre LIKE :titre OR l.auteur LIKE :auteur
                ORDER BY l.titre';
        $stmt = self::db()->prepare($sql);
        $like = '%' . $terme . '%';
        $stmt->execute([':titre' => $like, ':auteur' => $like]);
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $sql = 'SELECT l.*, ' . self::DISPONIBLES_SQL . ' AS disponibles
                FROM livres l
                WHERE l.id = :id';
        $stmt = self::db()->prepare($sql);
        $stmt->execute([':id' => $id]);
        $livre = $stmt->fetch();
        return $livre ?: null;
    }

    public static function create(array $data): int
    {
        $sql = 'INSERT INTO livres (titre, auteur, categorie, annee, nb_exemplaires)
                VALUES (:titre, :auteur, :categorie, :annee, :nb_exemplaires)';
        $stmt = self::db()->prepare($sql);
        $stmt->execute([
            ':titre'          => $data['titre'],
            ':auteur'         => $data['auteur'],
            ':categorie'      => $data['categorie'],
            ':annee'          => (int) $data['annee'],
            ':nb_exemplaires' => (int) $data['nb_exemplaires'],
        ]);
        return (int) self::db()->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $sql = 'UPDATE livres
                SET titre = :titre, auteur = :auteur, categorie = :categorie,
                    annee = :annee, nb_exemplaires = :nb_exemplaires
                WHERE id = :id';
        $stmt = self::db()->prepare($sql);
        return $stmt->execute([
            ':titre'          => $data['titre'],
            ':auteur'         => $data['auteur'],
            ':categorie'      => $data['categorie'],
            ':annee'          => (int) $data['annee'],
            ':nb_exemplaires' => (int) $data['nb_exemplaires'],
            ':id'             => $id,
        ]);
    }

    public static function delete(int $id): bool
    {
        try {
            $stmt = self::db()->prepare('DELETE FROM livres WHERE id = :id');
            $stmt->execute([':id' => $id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function estDisponible(int $id): bool
    {
        $livre = self::find($id);
        return $livre !== null && (int) $livre['disponibles'] > 0;
    }
}