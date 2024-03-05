<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="styles/styles.css"> 
</head>
<body>
    <?php include('header_frontend.php'); ?>
    <div class="container">
        <h2>Inscription</h2>
        <!-- Zone pour afficher les erreurs -->
        <?php
            if(isset($_GET["error"])) {
                if($_GET["error"] == "PasswordsDoNotMatch")
                echo "<div class='error'>Les deux mots de passe ne corresponde pas !</div>";
                elseif($_GET["error"] == "UsernameTaken")
                echo "<div class='error'>Le nom de l'utilisateur existe déjà !</div>";
                else echo "<div class='error'>Erreur de connection à la base !</div>";
            }
        ?>
        <form action="signup.php" method="post">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" required minlength="3"><br>
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required  minlength="4"><br>
            <label for="confirm_password">Confirmer le mot de passe</label>
            <input type="password" id="confirm_password" name="confirm_password" required><br>
            <input type="submit" value="S'inscrire">
        </form>
    </div>
</body>
</html>
