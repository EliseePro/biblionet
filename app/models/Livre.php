<?php

require_once __DIR__ . '/../../config/config.php';

class Livre
{

    private const DISPONIBLES = 'GREATEST(CAST(l.nb_exemplaires AS SIGNED) - (SELECT COUNT(*) FROM emprunts e WHERE e.livre_id = l.id AND e.date_retour IS NULL), 0)';

  
    private static function requete(string $sql, array $params = []): PDOStatement
    {
        $stmt = connexion()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function all(): array
    {
        $sql = 'SELECT l.*, ' . self::DISPONIBLES . ' AS disponibles FROM livres l ORDER BY l.titre, l.id';
        return self::requete($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function compter(?string $terme = null): int
    {
        if ($terme === null || $terme === '') {
            return (int) self::requete('SELECT COUNT(*) FROM livres')->fetchColumn();
        }

        $like = '%' . $terme . '%';
        return (int) self::requete(
            'SELECT COUNT(*) FROM livres WHERE titre LIKE ? OR auteur LIKE ?',
            [$like, $like]
        )->fetchColumn();
    }

    public static function pagines(int $limite, int $offset, ?string $terme = null): array
    {
        $limite = max(1, min($limite, 100));
        $offset = max(0, $offset);
        $sql = 'SELECT l.*, ' . self::DISPONIBLES . ' AS disponibles FROM livres l';
        $params = [];

        if ($terme !== null && $terme !== '') {
            $sql .= ' WHERE l.titre LIKE ? OR l.auteur LIKE ?';
            $like = '%' . $terme . '%';
            $params = [$like, $like];
        }

        $sql .= ' ORDER BY l.titre, l.id LIMIT ' . $limite . ' OFFSET ' . $offset;
        return self::requete($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function statistiques(): array
    {
        $sql = 'SELECT COUNT(*) AS titres,
                       COALESCE(SUM(l.nb_exemplaires), 0) AS exemplaires,
                       COALESCE(SUM(' . self::DISPONIBLES . '), 0) AS disponibles
                FROM livres l';
        return self::requete($sql)->fetch(PDO::FETCH_ASSOC);
    }

    public static function search(string $terme): array
    {
        $sql = 'SELECT l.*, ' . self::DISPONIBLES . ' AS disponibles FROM livres l
                WHERE l.titre LIKE ? OR l.auteur LIKE ? ORDER BY l.titre';
        $like = '%' . $terme . '%';
        return self::requete($sql, [$like, $like])->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find(int $id): ?array
    {
        $sql = 'SELECT l.*, ' . self::DISPONIBLES . ' AS disponibles FROM livres l WHERE l.id = ?';
        return self::requete($sql, [$id])->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function create(array $d): bool
    {
        $sql = 'INSERT INTO livres (titre, auteur, categorie, annee, nb_exemplaires) VALUES (?, ?, ?, ?, ?)';
        return self::requete($sql, [$d['titre'], $d['auteur'], $d['categorie'], (int) $d['annee'], (int) $d['nb_exemplaires']])->rowCount() > 0;
    }

    public static function update(int $id, array $d): bool
    {
        $sql = 'UPDATE livres SET titre = ?, auteur = ?, categorie = ?, annee = ?, nb_exemplaires = ? WHERE id = ?';
        self::requete($sql, [$d['titre'], $d['auteur'], $d['categorie'], (int) $d['annee'], (int) $d['nb_exemplaires'], $id]);
        return true;
    }

   
    public static function delete(int $id): bool
    {
        try {
            return self::requete('DELETE FROM livres WHERE id = ?', [$id])->rowCount() > 0;
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