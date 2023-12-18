<?php
session_start();
include('base.php');

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: connexion.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_groupe = $_POST["nom_groupe"];
    $description_groupe = $_POST["description_groupe"];
    $createur = $_SESSION["username"]; // Nom de l'utilisateur connecté

    $insert_groupe = "INSERT INTO t_groupe_grp (nom_grp, desc_grp, type_grp, admin) VALUES ('$nom_groupe', '$description_groupe', 'prive', '$createur')";
    $conn->query($insert_groupe);

    $groupe_id = $conn->insert_id;

    $membres = $_POST["membres"];
    $membres_array = explode(",", $membres);
    foreach ($membres_array as $membre) {
        $membre = trim($membre);

        $sql_utilisateur = "SELECT id_uti FROM t_utilisateur_uti WHERE nom_uti = '$membre'";
        $result_utilisateur = $conn->query($sql_utilisateur);

        if ($result_utilisateur->num_rows == 1) {
            $row_utilisateur = $result_utilisateur->fetch_assoc();
            $id_utilisateur = $row_utilisateur["id_uti"];

            $insert_membre = "INSERT INTO t_possede_pos (id_uti, id_grp) VALUES ($id_utilisateur, $groupe_id)";
            $conn->query($insert_membre);
        }
    }

    // Ajout du créateur du groupe dans t_possede_pos
    $sql_createur = "SELECT id_uti FROM t_utilisateur_uti WHERE nom_uti = '$createur'";
    $result_createur = $conn->query($sql_createur);

    if ($result_createur->num_rows == 1) {
        $row_createur = $result_createur->fetch_assoc();
        $id_createur = $row_createur["id_uti"];

        $insert_createur = "INSERT INTO t_possede_pos (id_uti, id_grp) VALUES ($id_createur, $groupe_id)";
        $conn->query($insert_createur);
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Créer un Groupe</title>
</head>
<body>
    <h1>Créer un Groupe</h1>
    <form method="POST">
        <label for="nom_groupe">Nom du Groupe:</label>
        <input type="text" name="nom_groupe" required><br>

        <label for="description_groupe">Description du Groupe:</label>
        <textarea name="description_groupe" required></textarea><br>

        <label for="membres">Membres (séparés par des virgules):</label>
        <input type="text" name="membres"><br>

        <input type="submit" value="Créer le Groupe">
    </form>
</body>
</html>
