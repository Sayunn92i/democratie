<?php
// Connexion à la base de données à modifier
$dbhost = "";
$dbuser = "";
$dbpass = "";
$dbname = "democratie_v2";

$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}
