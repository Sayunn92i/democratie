<?php
session_start();
include('base.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: connexion.php"); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}

$sql_liste_sondages = 'SELECT p.*, GROUP_CONCAT(pa.titre_rep ORDER BY pa.id_rep) AS answers FROM t_sondage_son p LEFT JOIN t_reponsesondage_rep pa ON pa.id_son = p.id_son GROUP BY p.id_son';
$result_liste_sondages = $conn->query($sql_liste_sondages);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Liste des sondages</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
</head>

<body>

    <h2>Sondages</h2>
    <p>Voici la liste des sondages.</p>
    <a href="creer_sondage.php" class="create-poll">Créer un sondage</a>
    <?php
    if ($result_liste_sondages->num_rows > 0) {
        ?>
        <table>
            <thead>
                <tr>
                    <td>#</td>
                    <td>Titre</td>
                    <td>Réponses</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                <?php while ($poll = $result_liste_sondages->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?= $poll['id_son'] ?>
                        </td>
                        <td>
                            <?= $poll['titre_son'] ?>
                        </td>
                        <td>
                            <?= $poll['answers'] ?>
                        </td>
                        <td class="actions">
                            <a href="vote_sondage.php?id_son=<?= $poll['id_son'] ?>" class="view" title="Voir le sondage"><i
                                    class="fas fa-eye fa-xs"></i></a>
                            <a href="supprimer_sondage.php?id_son=<?= $poll['id_son'] ?>" class="trash" title="Supprimer le sondage"><i
                                    class="fas fa-trash fa-xs"></i></a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php } else {
        echo "Aucun sondage trouvé.";
    } ?>
</body>

</html>