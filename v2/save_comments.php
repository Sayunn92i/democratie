<?php
session_start();
include('base.php');

if (isset($_POST['comments'])) {
    $comments = json_decode($_POST['comments'], true);

    foreach ($comments as $comment) {
        $content = $comment['comment'];
        $id_pro = $comment['id_pro']; // Assurez-vous que votre tableau contient l'id_pro
        $id_uti = $_SESSION['id_uti']; // Remplacez cela par la manière dont vous obtenez l'id de l'utilisateur
        echo $content;
        echo $id_pro;
        echo $id_uti;
        // Vérifier si le commentaire existe déjà
        $sql_check = "SELECT id_com FROM t_commentaire_com WHERE contenu_com = '$content' AND id_pro = $id_pro AND id_uti = $id_uti";
        $result_check = $conn->query($sql_check);

        if ($result_check->num_rows == 0) {
            // Le commentaire n'existe pas, nous pouvons l'insérer
            $sql_insert = "INSERT INTO t_commentaire_com (contenu_com, id_pro, id_uti, datecrea_com, num_debut_com, num_fin_com) VALUES ('$content', $id_pro, $id_uti, NOW(), 0, 0)";
            $conn->query($sql_insert);
        }
    }

    echo 'Comment inserted successfully';
} else {
    echo 'Invalid request';
}
?>
