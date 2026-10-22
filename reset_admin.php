<?php
// FICHIER DE TEST LOCAL : à supprimer ensuite, ne pas l'envoyer sur GitHub
require __DIR__ . '/config/config.php';

$hash = password_hash('admin123', PASSWORD_DEFAULT);
$stmt = connexion()->prepare('UPDATE users SET password = ? WHERE email = ?');
$stmt->execute([$hash, 'admin@biblionet.local']);

echo 'Lignes modifiées : ' . $stmt->rowCount() . PHP_EOL;