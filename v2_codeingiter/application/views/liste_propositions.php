<!DOCTYPE html>
<html>
<head>
    <title>Liste des propositions</title>
</head>
<body>
    <?php if (!empty($propositions)) : ?>
        <h1>Liste des propositions :</h1>
        <!-- pour chaque proposition, on affiche l'id le titre, le contenu, le groupe et les utilisateurs du groupe -->
        <?php foreach ($propositions as $proposition) : ?>
            <div>
                <p>ID de la Proposition: <?php echo $proposition['id_pro']; ?> - Titre: <?php echo $proposition['titre_pro']; ?> - Contenu: <?php echo $proposition['contenu_pro']; ?> - Groupe: <?php echo $proposition['nom_grp']; ?></p>
                <?php if (!empty($proposition['utilisateurs'])) : ?>
                    <p>Utilisateurs dans ce groupe : <?php echo $proposition['utilisateurs']; ?></p>
                <?php endif; ?>
                <!-- affiche un bouton modifier et supprimer si l'utilisateur appartient au groupe -->
                <?php if (!empty($proposition['utilisateurs']) && strpos($proposition['utilisateurs'], $this->session->userdata('username')) !== false) : ?>
                    <form action="<?php echo base_url('espace_prive/modifier_proposition/'.$proposition['id_pro']); ?>" method="post">
                        <button type="submit">Modifier</button>
                    </form>

                    <form action="<?php echo base_url('espace_prive/delete_proposition/'.$proposition['id_pro']); ?>" method="post">
                        <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette proposition ?')">Supprimer</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p>Aucune proposition trouvée</p>
    <?php endif; ?>
</body>
</html>
