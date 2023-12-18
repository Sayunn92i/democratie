<?php
session_start();

// Vérification du token
if ($_SERVER["REQUEST_METHOD"] == "POST" && hash_equals($_SESSION["token"], $_POST["token"])) {
    // Destruction de la session
    session_destroy();
    // Redirection vers la page de connexion
    header("location: connexion.php");
    exit;
} else {
    // En cas de token invalide, vous pouvez gérer l'erreur ici
    echo "Erreur de déconnexion.";
}
?>
