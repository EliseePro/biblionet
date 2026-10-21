<?php

require_once __DIR__ . '/../../config/config.php';

class User
{
    private static function requete(string $sql, array $params = []): PDOStatement
    {
        $stmt = connexion()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function findByEmail(string $email): ?array
    {
        $row = self::requete('SELECT * FROM users WHERE email = ?', [$email])->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public static function find(int $id): ?array
    {
        $row = self::requete('SELECT id, nom, email FROM users WHERE id = ?', [$id])->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public static function authenticate(string $email, string $password): ?array
    {
        $user = self::findByEmail($email);
        if ($user === null) {
            return null;
        }
        $hash = $user['password'];
        $ok = password_verify($password, $hash)
            || (!str_starts_with($hash, '$2y$') && !str_starts_with($hash, '$argon') && hash_equals($hash, $password));
        if (!$ok) {
            return null;
        }
        unset($user['password']);
        return $user;
    }
}
