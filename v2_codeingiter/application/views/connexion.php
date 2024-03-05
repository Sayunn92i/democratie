<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Page de connexion -->
    <title>Connexion</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/styles.css'); ?>">
</head>
<body>
<?php $this->load->view('header_frontend'); ?>   
    <div class="container">
        <h2>Connexion</h2>
        <!-- Zone pour afficher les erreurs -->
        <?php
            if(isset($_GET["error"])) {
                if($_GET["error"] == "WrongPassword")
                echo "<div class='error'>Mauvais mot de passe !</div>";
                elseif($_GET["error"] == "Mauvais nom d'utilisateur !")
                echo "<div class='error'>Le nom de l'utilisateur existe déjà !</div>";
                else echo "<div class='error'>Erreur de connection à la base !</div>";
            }
        ?>
        <!-- Formulaire de connexion -->
        <form action="<?php echo base_url('connexion/connexion_submit'); ?>" method="post">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" required><br>
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required><br>
            <input type="submit" value="Se connecter">
        </form>
    </div>
</body>
</html>
