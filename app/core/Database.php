<?php

class Database
{
    private static ?PDO $pdo = null;

    public static function getConnection(): PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        $host     = getenv('DB_HOST') ?: 'localhost';
        $database = getenv('DB_NAME') ?: 'bibliotheque';
        $user     = getenv('DB_USER') ?: 'root';
        $password = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';

        self::$pdo = new PDO(
            "mysql:host={$host};dbname={$database};charset=utf8mb4",
            $user,
            $password,
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]
        );

        return self::$pdo;
    }
}
