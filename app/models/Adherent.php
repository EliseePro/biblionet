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
        $sql = 'SELECT *  FROM adherents';
        return self::requete($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

public static function trouver(int $id): ?array
    {
        $sql = 'SELECT * FROM adherents WHERE id = ?';
        return self::requete($sql, [$id])->fetch(PDO::FETCH_ASSOC) ?: null;
    }

public static function creer(array $d): bool
    {
        $sql = 'INSERT INTO adherents (nom, prenom, classe, telephone) VALUES (?, ?, ?, ?)';
        return self::requete($sql, [$d['nom'], $d['prenom'], $d['classe'], $d['telephone']])->rowCount() > 0;
    }

public static function modifier(int $id, array $d): bool
    {
        $sql = 'UPDATE adherents SET nom = ?, prenom = ?, classe = ?, telephone = ? WHERE id = ?';
        self::requete($sql, [$d['nom'], $d['prenom'], $d['classe'], $d['telephone'], $id]);
        return true;
    }

public static function supprimer(int $id): bool
    {
        try {
            return self::requete('DELETE FROM adherents WHERE id = ?', [$id])->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }


}