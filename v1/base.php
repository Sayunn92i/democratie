<?php
// Connexion à la base de données
$dbhost = "localhost";
$dbuser = "clementf";
$dbpass = "2II8ZgcZoYt6M4Cx";
$dbname = "democratie_v1";

$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}