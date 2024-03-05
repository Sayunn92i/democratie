<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Page d'accueil après la connexion -->
    <title>Accueil</title>

</head>

<body>
    <!-- Affiche le header -->
    <?php $this->load->view('header_backend'); ?> 
    <div class="container">
        <h2>Bienvenue sur notre plateforme,
            <?php echo $this->session->userdata('username'); ?>
        </h2>
        <p>Contenu de la page d'accueil...</p>
    </div>
</body>


</html>