<?php
session_start();
include('base.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: connexion.php"); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}

// Récupérer l'ID de l'utilisateur connecté
$username = $_SESSION["username"];
$sql = "SELECT id_uti FROM t_utilisateur_uti WHERE nom_uti = '$username'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $id_utilisateur = $row["id_uti"];

    // Requête pour récupérer toutes les propositions
    $sql_propositions = "SELECT t_proposition_pro.*, t_groupe_grp.nom_grp, t_possede_pos.id_uti AS id_appartient
                        FROM t_proposition_pro
                        JOIN t_groupe_grp ON t_proposition_pro.id_grp = t_groupe_grp.id_grp
                        LEFT JOIN t_possede_pos ON t_proposition_pro.id_grp = t_possede_pos.id_grp AND t_possede_pos.id_uti = $id_utilisateur";
    $result_propositions = $conn->query($sql_propositions);

    // Afficher les propositions
    if ($result_propositions->num_rows > 0) {
        echo "<h1>Liste des propositions :</h1>";
        while ($row_proposition = $result_propositions->fetch_assoc()) {
            echo "ID de la Proposition: " . $row_proposition["id_pro"]. " - Titre: " . $row_proposition["titre_pro"]. " - Contenu: " . $row_proposition["contenu_pro"] . " - Groupe: " . $row_proposition["nom_grp"];
            
            // Ajouter des boutons "Modifier" et "Supprimer" pour les propositions de l'utilisateur
            if ($row_proposition["id_appartient"] == $id_utilisateur) {
                echo "<a href='modifier_proposition.php?id_pro=" . $row_proposition["id_pro"] . "'> Modifier </a>";
                echo "<a href='supprimer_proposition.php?id_pro=" . $row_proposition["id_pro"] . "'> Supprimer </a>";
            }
            
            echo "<br>";
        }
    } else {
        echo "Aucune proposition trouvée";
    }
} else {
    echo "Utilisateur non trouvé";
}

$conn->close();
?>
