<header>
    <?php if(basename($_SERVER['PHP_SELF']) != 'connexion.php' && basename($_SERVER['PHP_SELF']) != 'inscription.php') : ?>
        <h1>Bienvenue sur notre site</h1>
    <?php else : ?>
        <h1><a href="index.php">Retour à l'accueil</a></h1>
    <?php endif; ?>
    <nav>
        <ul>
            <?php if(basename($_SERVER['PHP_SELF']) != 'connexion.php') : ?>
                <li><a href="connexion.php">Se connecter</a></li>
            <?php endif; ?>
            <?php if(basename($_SERVER['PHP_SELF']) != 'inscription.php') : ?>
                <li><a href="inscription.php">S'inscrire</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
