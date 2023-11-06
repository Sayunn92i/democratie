<?php
session_start();

// Vérification de la session
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: connexion.php"); // Redirige l'utilisateur vers la page de connexion s'il n'est pas connecté
    exit;
}

// Génération d'un token de session
if (!isset($_SESSION["token"])) {
    $_SESSION["token"] = bin2hex(random_bytes(32)); // Génère un token de 32 caractères
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>

<body>
    
    <?php include('header_backend.php');?>
    <div class="container">
        <h2>Bienvenue sur notre plateforme, <?php echo $_SESSION["username"]; ?></h2>
        <p>Contenu de la page d'accueil...</p>
    </div>
</body>

</html>

