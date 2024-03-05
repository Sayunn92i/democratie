<?php
include('base.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les informations du formulaire
    $username = $_POST["username"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Vérifier si le nom d'utilisateur est déjà utilisé
    $check_username = "SELECT * FROM t_utilisateur_uti WHERE nom_uti='$username'";
    $result = $conn->query($check_username);

    if ($result->num_rows == 0) {
        // Vérifier que les mots de passe correspondent
        if ($password === $confirm_password) {
            // Si le nom d'utilisateur n'est pas déjà utilisé et les mots de passe correspondent, enregistrer l'utilisateur
            $hashed_password = hash('sha256', $password);

            $insert_user = "INSERT INTO t_utilisateur_uti (nom_uti, mdp_uti, type_uti) VALUES ('$username', '$hashed_password', 'Utilisateur')";
            
            if ($conn->query($insert_user) === TRUE) {
                // Rediriger vers une page de confirmation ou autre
                header("location: connexion.php");
                exit();
            } else {
                echo "Erreur : " . $conn->error;
            }
        } else {
            header("location: inscription.php?error=PasswordsDoNotMatch");
            exit();
        }
    } else {
        header("location: inscription.php?error=UsernameTaken");
        exit();
    }
}

$conn->close();
?>
