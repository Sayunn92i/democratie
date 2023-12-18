<?php
session_start();
include('base.php');
 
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les informations du formulaire
    $username = $_POST["username"];
    $password = $_POST["password"];

    

    // Requête pour vérifier les informations d'identification
    $sql = "SELECT * FROM t_utilisateur_uti WHERE nom_uti='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['mdp_uti'];
        $user_id = $row['id_uti']; // Récupération de l'ID de l'utilisateur
        $user_role = $row['type_uti'];

        // Vérification du mot de passe
        if (hash('sha256', $password) == $hashed_password) {
            // Créer une session
            $_SESSION["loggedin"] = true;
            $_SESSION["username"] = $username;
            $_SESSION["user_id"] = $user_id;
            $_SESSION["user_role"] = $user_role;
            

            // Rediriger vers une page de bienvenue ou autre
            header("location: espace_prive.php");
            exit();
        } else {
            header("location: connexion.php?error=WrongPassword");
           
        }
    } else {
        header("location: connexion.php?error=WrongUsername");
        exit();
        
    }
            
    $conn->close();
    exit();
}
?>
