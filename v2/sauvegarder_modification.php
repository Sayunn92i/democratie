<?php
session_start();
include('base.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: connexion.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id_pro"])) {
    $id_proposition = $_GET["id_pro"];

    // Vérifie si la proposition est bien verrouillée avant d'enregistrer
    $sql_check_lock = "SELECT verrou FROM t_proposition_pro WHERE id_pro = $id_proposition";
    $result_check_lock = $conn->query($sql_check_lock);

    if ($result_check_lock->num_rows == 1) {
        $row = $result_check_lock->fetch_assoc();
        if ($row["verrou"] == 1) {
            // Verrou trouvé, traite l'enregistrement des modifications
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Récupérer les données du formulaire
                // Mettre à jour la proposition dans la base de données
                // ...

                // Déverrouiller la proposition après enregistrement des modifications
                $sql_unlock_proposition = "UPDATE t_proposition_pro SET verrou = 0 WHERE id_pro = $id_proposition";
                $conn->query($sql_unlock_proposition);
                header("location: liste_propositions.php");
                exit();
                
            } 
        } 
    } 
} 
?>
