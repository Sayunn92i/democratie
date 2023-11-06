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
?>

<!DOCTYPE html>
<html>
<head>
</head>
<body>
<?php
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
                
                echo "<form action='modifier_proposition.php' method='get'>";
                echo "<input type='hidden' name='id_pro' value='" . $row_proposition["id_pro"] . "'>";
                echo "<button type='submit'>Modifier</button>";
                echo "</form>";
                echo "<form action='supprimer_proposition.php' method='get'>";
                echo "<input type='hidden' name='id_pro' value='" . $row_proposition["id_pro"] . "'>";
                echo "<button type='submit'>Supprimer</button>";
                echo "</form>";
                
    
                echo "<br>";
            }

            // Récupérer les commentaires pour cette proposition
            $id_proposition = $row_proposition["id_pro"];
            $sql_commentaires = "SELECT * FROM t_commentaire_com WHERE id_pro = $id_proposition";
            $result_commentaires = $conn->query($sql_commentaires);

            // Afficher les commentaires
            if ($result_commentaires->num_rows > 0) {
                echo "<h2>Commentaires :</h2>";
                while ($row_commentaire = $result_commentaires->fetch_assoc()) {
                    echo "Contenu du commentaire: " . $row_commentaire["contenu_com"]. " - Date de création: " . $row_commentaire["datecrea_com"];
                    echo "<br>";
                }
            } else {
                echo "Aucun commentaire pour cette proposition";
            }

            // Formulaire d'ajout de commentaire
            echo "<form>";
            echo "<input type='text' name='contenu_com' required>";
            echo "<button type='submit'>Ajouter un commentaire</button>";
            echo "</form>";

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
</body>
</html>
