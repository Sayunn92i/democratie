<?php
session_start();
include('base.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_utilisateur = $_SESSION["id_uti"];
    $id_proposition = $_POST["id_pro"];
    $contenu_com = $_POST["contenu_com"];
    $datecrea_com = date("Y-m-d");

    $sql_insert_commentaire = "INSERT INTO t_commentaire_com (id_uti, id_pro, contenu_com, datecrea_com) VALUES ('$id_utilisateur', '$id_proposition', '$contenu_com', '$datecrea_com')";
    $conn->query($sql_insert_commentaire);
}
?>
