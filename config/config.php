<?php


function loadEnv(string $path): void
{
    if (!is_file($path)) {
        return;
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return;
    }
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        if (!str_contains($line, '=')) {
            continue;
        }
        [$name, $value] = explode('=', $line, 2);
        $name  = trim($name);
        $value = trim($value);
        if (
            (str_starts_with($value, '"') && str_ends_with($value, '"')) ||
            (str_starts_with($value, "'") && str_ends_with($value, "'"))
        ) {
            $value = substr($value, 1, -1);
        }
        if ($name !== '' && getenv($name) === false) {
            putenv("{$name}={$value}");
            $_ENV[$name] = $value;
        }
    }
}

$envPath = dirname(__DIR__) . '/.env';
loadEnv($envPath);

function app_base_path(): string
{
    $scriptDirectory = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    return $scriptDirectory === '/' || $scriptDirectory === '.' ? '' : rtrim($scriptDirectory, '/');
}

function app_url(string $path = '/'): string
{
    $path = '/' . ltrim($path, '/');
    return app_base_path() . $path;
}

function connexion(): PDO
{
    static $bdd = null;
    if ($bdd instanceof PDO) {
        return $bdd;
    }

    $host     = getenv('DB_HOST');
    $database = getenv('DB_NAME');
    $user     = getenv('DB_USER');
    $password = getenv('DB_PASS');

    $host     = ($host === false || $host === '') ? 'sql211.infinityfree.com' : $host;
    $database = ($database === false || $database === '') ? 'if0_43246115_bibliotheque' : $database;
    $user     = ($user === false || $user === '') ? 'if0_43246115' : $user;
    $password = ($password === false) ? '' : $password;

    $bdd = new PDO(
        "mysql:host={$host};dbname={$database};charset=utf8mb4",
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
    return $bdd;
}
