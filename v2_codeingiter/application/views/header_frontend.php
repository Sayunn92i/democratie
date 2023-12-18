<header>
    <?php if($this->uri->segment(1) != 'connexion' && $this->uri->segment(1) != 'inscription') : ?>
        <h1>Bienvenue sur notre site</h1>
    <?php else : ?>
        <h1><a href="<?php echo base_url(); ?>">Retour à l'accueil</a></h1>
    <?php endif; ?>
    <nav>
        <ul>
            <?php if($this->uri->segment(1) != 'connexion') : ?>
                <li><a href="<?php echo base_url('connexion'); ?>">Se connecter</a></li>
            <?php endif; ?>
            <?php if($this->uri->segment(1) != 'inscription') : ?>
                <li><a href="<?php echo base_url('inscription'); ?>">S'inscrire</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
