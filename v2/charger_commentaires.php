<?php
session_start();
include('base.php');
echo "test";
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id_pro"])) {
    $id_proposition = $_GET["id_pro"];
    $sql_commentaires = "SELECT * FROM t_commentaire_com WHERE id_pro = $id_proposition";
    $result_commentaires = $conn->query($sql_commentaires);

    if ($result_commentaires->num_rows > 0) {
        while ($row_commentaire = $result_commentaires->fetch_assoc()) {
            echo "Contenu: " . $row_commentaire["contenu_com"] . " - Utilisateur: " . $row_commentaire["id_uti"] . " - Date de création: " . $row_commentaire["datecrea_com"];
            echo "<br>";
        }
    } else {
        echo "Aucun commentaire trouvé";
    }
}
?>
