<?php
// FICHIER DE TEST LOCAL : ne pas l'envoyer sur GitHub
$chemin = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Fichiers statiques (css, js, images) servis tels quels
if ($chemin !== '/' && is_file(__DIR__ . $chemin)) {
    return false;
}

// Remplace le travail du .htaccess
$_GET['url'] = ltrim($chemin, '/');
require __DIR__ . '/index.php';