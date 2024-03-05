<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Page d'acceuil du site -->
    <title>Accueil</title>

</head>

<body>
    <header>
        <nav>
            <ul>
                <!-- Lien vers la page de connexion -->
                <li><a href="<?php echo base_url('connexion'); ?>">Connexion</a></li>
                <!-- Lien vers la page d'inscription -->
                <li><a href="<?php echo base_url('inscription'); ?>">Inscription</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2>Contenu de la page d'accueil</h2>

    </div>
</body>

</html>