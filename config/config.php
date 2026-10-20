// connexion à la base de données avec une fonction
<?php
function connexion() {
    $bdd = new PDO('mysql:host=localhost;dbname=bibliotheque;charset=utf8', 'root', 'root');
    return $bdd;
}