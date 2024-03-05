<header>
        <h1><a href="espace_prive.php">Menu</a></h1>
        <nav>
            <ul>
                <li><a href="creer_proposition_v2.php">Creer une proposition</a></li>
                <li><a href="liste_propositions.php">Liste des propositions</a></li>
                <li><a href="liste_groupes.php">Groupes</a></li>
                <li><a href="creer_groupe.php">Creer un groupe</a></li>
                <li><a href="liste_sondages.php">Sondages</a></li>
                <li>
                    <form action="deconnexion.php" method="post">
                    <input type="hidden" name="token" value="<?php echo $_SESSION["token"]; ?>">
                    <input class="logout-button" type="submit" value="Se Déconnecter">
                    </form>
                </li>
            </ul>
        </nav>
</header>