<?php
session_start();
include('base.php');

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id_pro"])) {
    $id_proposition = $_GET["id_pro"];

    // Mettre à jour le verrou de la proposition dans la base de données à 0 pour la déverrouiller
    $sql_unlock_proposition = "UPDATE t_proposition_pro SET verrou = 0 WHERE id_pro = $id_proposition";
    $conn->query($sql_unlock_proposition);
    
    exit();
}
?>
