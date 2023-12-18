<?php
session_start();
include('base.php');

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id_pro"])) {
    $id_proposition = $_GET["id_pro"];

    // Supprimer les versions associées de la proposition dans la table modif
    $sql_delete_versions = "DELETE FROM t_modification_mod WHERE id_pro = $id_proposition";
    $conn->query($sql_delete_versions);

    // Supprimer la proposition de la table t_proposition_pro
    $sql_delete_proposition = "DELETE FROM t_proposition_pro WHERE id_pro = $id_proposition";
    if ($conn->query($sql_delete_proposition) === TRUE) {
        // Redirection vers une page appropriée après la suppression
        header("location: liste_propositions.php");
        exit();
    } else {
        echo "Erreur lors de la suppression de la proposition : " . $conn->error;
    }
} else {
    // Gérer les cas où l'ID de la proposition n'est pas défini ou la méthode n'est pas GET
    echo "Erreur : ID de proposition non spécifié ou méthode incorrecte.";
}

$conn->close();
?>
