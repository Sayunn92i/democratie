<?php
// Démarre la session
session_start();

// Supprime toutes les variables de session
$_SESSION = array();

// Détruit la session
session_destroy();

// Supprime le cookie d'ID utilisateur
if (isset($_COOKIE['user_id'])) {
    setcookie('user_id', '', time() - 3600, '/');
}

// Redirige vers la page de connexion
header('location: connexion.php');
exit();
?>
