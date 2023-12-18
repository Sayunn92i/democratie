<?php
session_start();
include('base.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: connexion.php");
    exit();
}

// Récupérer l'ID de l'utilisateur connecté
$username = $_SESSION["username"];
$sql_id_utilisateur = "SELECT id_uti FROM t_utilisateur_uti WHERE nom_uti = '$username'";
$result_id_uti = $conn->query($sql_id_utilisateur);
$row_id_uti = $result_id_uti->fetch_assoc();
$id_utilisateur = $row_id_uti["id_uti"];

// Récupérer tous les groupes publics
$sql_groupes_publics = "SELECT id_grp, nom_grp, type_grp, desc_grp FROM t_groupe_grp WHERE type_grp = 'public'";
$result_groupes_publics = $conn->query($sql_groupes_publics);

// Récupérer les groupes publics ou privés de l'utilisateur
$sql_groupes_utilisateur = "SELECT t_groupe_grp.id_grp, t_groupe_grp.nom_grp, t_groupe_grp.type_grp, t_groupe_grp.desc_grp
                            FROM t_groupe_grp
                            JOIN t_possede_pos ON t_groupe_grp.id_grp = t_possede_pos.id_grp
                            WHERE t_possede_pos.id_uti = $id_utilisateur";
$result_groupes_utilisateur = $conn->query($sql_groupes_utilisateur);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Liste des Groupes</title>
</head>
<body>
    <h1>Liste des Groupes</h1>

    <h2>Groupes Publics</h2>
    <ul>
        <?php
        while ($row = $result_groupes_publics->fetch_assoc()) {
            echo "<li>" . $row["nom_grp"] . " : " . $row["desc_grp"] ."</li>";
        }
        ?>
    </ul>

    <h2>Vos Groupes (Publics et Privés)</h2>
<ul>
    <?php
    while ($row = $result_groupes_utilisateur->fetch_assoc()) {
        echo "<li>" . $row["nom_grp"] . " (" . $row["type_grp"] . ") : " . $row["desc_grp"] . "<br>";
        
        // Ajoutez une requête pour récupérer les noms des utilisateurs dans ce groupe
        $groupeId = $row["id_grp"];
        $sql_utilisateurs_groupe = "SELECT t_utilisateur_uti.nom_uti
                                    FROM t_utilisateur_uti
                                    JOIN t_possede_pos ON t_utilisateur_uti.id_uti = t_possede_pos.id_uti
                                    WHERE t_possede_pos.id_grp = $groupeId";
        $result_utilisateurs_groupe = $conn->query($sql_utilisateurs_groupe);

        echo "Utilisateurs dans ce groupe : ";
        while ($row_utilisateur = $result_utilisateurs_groupe->fetch_assoc()) {
            echo $row_utilisateur["nom_uti"] . ", ";
        }
        echo "</li>";
    }
    ?>
</ul>

</body>
</html>
