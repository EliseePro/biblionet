<?php

function connexion(): PDO
{
    static $bdd = null;
    if ($bdd instanceof PDO) {
        return $bdd;
    }

    $host = getenv('DB_HOST');
    $database = getenv('DB_NAME');
    $user = getenv('DB_USER');
    $password = getenv('DB_PASS');

    $host = $host === false || $host === '' ? 'localhost' : $host;
    $database = $database === false || $database === '' ? 'bibliotheque' : $database;
    $user = $user === false || $user === '' ? 'root' : $user;
    $password = $password === false ? '' : $password;

    $bdd = new PDO(
        "mysql:host={$host};dbname={$database};charset=utf8mb4",
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
    return $bdd;
}