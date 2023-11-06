<?php
session_start();
include('base.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: connexion.php"); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}

// Traitement du formulaire de création de proposition
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = $conn->real_escape_string($_POST["titre"]); // Éviter les injections SQL
    $contenu = $conn->real_escape_string($_POST["contenu"]); // Éviter les injections SQL
    $groupe_id = (int)$_POST["groupe_id"];

    // Insérer la proposition dans la base de données
    $insert_proposition = "INSERT INTO t_proposition_pro (titre_pro, contenu_pro, statut_pro, id_grp) VALUES ('$titre', '$contenu', 'En attente', $groupe_id)";
    $conn->query($insert_proposition);

    // Récupérer l'ID de la proposition nouvellement créée
    $proposition_id = $conn->insert_id;

    // Rediriger vers une page de succès ou autre
    echo $titre;
    echo $groupe_id;
    echo "proposition ajoutée";
    echo $insert_proposition;
    exit();
}

// Récupérer la liste des groupes de l'utilisateur
$sql_id_utilisateur = "SELECT id_uti FROM t_utilisateur_uti WHERE (nom_uti = '{$_SESSION["username"]}')"; 
$result_id_uti = $conn->query($sql_id_utilisateur);
$row_id_uti = $result_id_uti->fetch_assoc();
$id_utilisateur = $row_id_uti["id_uti"];

$sql_groupes = "SELECT id_grp, nom_grp FROM t_groupe_grp WHERE id_grp IN (SELECT id_grp FROM t_possede_pos WHERE id_uti = $id_utilisateur)";
$result_groupes = $conn->query($sql_groupes);

// Créer un tableau pour stocker les groupes
$groupes = array();
while ($row = $result_groupes->fetch_assoc()) {
    $groupes[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Créer une proposition</title>
</head>
<body>
    <h1>Créer une proposition</h1>
    <form method="POST">
        <label for="titre">Titre de la proposition:</label>
        <input type="text" name="titre" id="titre" required><br>

        <label for="contenu">Contenu de la proposition:</label>
        <textarea name="contenu" id="contenu" required></textarea><br>

        <label for="groupe_id">Sélectionnez un groupe:</label>
        <select name="groupe_id" id="groupe_id">
            <?php
            foreach ($groupes as $groupe) {
                echo "<option value=\"" . $groupe["id_grp"] . "\">" . $groupe["nom_grp"] . "</option>";
            }
            ?>
        </select><br>


        <input type="submit" value="Créer la proposition">
    </form>

</body>
</html>

