<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>

<head>
    <title>Modifier une proposition</title>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <h1>Bonjour
        <?php echo $this->session->userdata('username'); ?>
    </h1>
    <!-- affiche une checkbox pour bloquer l'edition si l'utilisateur est le créateur du groupe -->
    <?php if ($is_admin): ?>
        <input type="checkbox" id="block-editor" name="block-editor">
        <label for="block-editor">Bloquer l'édition pour les autres utilisateurs</label>
    <?php endif; ?>
    <!-- Afficher les utilisateurs connectés -->
    <h3>Utilisateurs connectés :</h3>
    <ul id="connectedUsers"></ul>
    <h1>Modifier une proposition</h1>
    <form method="POST" action="<?php echo site_url('EspacePrive/updateProposition'); ?>">
        <label for="titre">Titre de la proposition:</label>
        <input type="text" name="titre" id="titre" value="<?php echo $proposition['titre_pro']; ?>" required>
        <br>
        <label for="contenu">Contenu de la proposition:</label>
        <div id="editor" style="height: 300px;">
            <?php echo $proposition['contenu_pro']; ?>
        </div>

        <input type="hidden" name="contenu" id="contenu">
        <input type="hidden" name="id_proposition" value="<?php echo $proposition['id_pro']; ?>">
        <button type="submit" name="enregistrer">Enregistrer les changements</button>

        <h2>Versions précédentes</h2>
        <!-- affichage des versions précedentes -->
        <ul id="versions-container">
            <?php if (!empty($versions)): ?>
                <?php foreach ($versions as $version): ?>
                    <li class="version-item" data-content="<?php echo htmlspecialchars($version['contenumodif_com']); ?>"
                        data-date="<?php echo htmlspecialchars($version['datecrea_mod']); ?>"
                        data-user="<?php echo htmlspecialchars($version['nom_uti']); ?>">
                        Version du
                        <?php echo htmlspecialchars($version['datecrea_mod']); ?> par
                        <?php echo htmlspecialchars($version['nom_uti']); ?>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucune version précédente disponible.</p>
            <?php endif; ?>
        </ul>

    </form>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
        var propositionId = "<?php echo $proposition['id_pro']; ?>";
        var username = "<?php echo $this->session->userdata('username'); ?>";
        var admin = "<?php echo $is_admin; ?>";
        //Websocket
        var socket = new WebSocket('ws://localhost:8080');
        console.log(socket);
        socket.onopen = function (event) {
            console.log(username + ' connecté au serveur WebSocket');
            socket.send(JSON.stringify({ type: 'connection_info', username: username, propositionId: propositionId }));
        };

        function updateConnectedUsers(users) {
            var connectedUsersList = document.getElementById('connectedUsers');
            connectedUsersList.innerHTML = '';
            users.forEach(function (user) {
                var li = document.createElement('li');
                li.textContent = user;
                connectedUsersList.appendChild(li);
            });
        };
        //Quill
        var toolbarOptions = [
            ["bold", "italic", "underline", "strike"],
            [{ list: "ordered" }, { list: "bullet" }],
            [{ script: "sub" }, { script: "super" }],
            [{ indent: "-1" }, { indent: "+1" }],
            [{ direction: "rtl" }],

            [{ size: ["small", false, "large", "huge"] }],
            [{ header: [1, 2, 3, 4, 5, 6, false] }],

            [{ color: [] }, { background: [] }],
            [{ font: [] }],
            [{ align: [] }],

            ["clean"],
        ];
        var quill = new Quill('#editor', {
            modules: {
                history: {
                    delay: 500,
                    maxStack: 500,
                    userOnly: true,
                },
                toolbar: toolbarOptions,

            },
            theme: "snow",
        });

        var savedRange;

        // Sauvegardez la position actuelle du curseur
        function saveCursorPosition() {
            savedRange = quill.getSelection();
        }

        // Restaurez la position du curseur à sa position précédente
        function restoreCursorPosition() {
            if (savedRange) {
                quill.setSelection(savedRange.index, savedRange.length);
            }
        }
        quill.on('text-change', function (delta, oldDelta, source) {
            if (source === 'user') {
                saveCursorPosition();
                socket.send(JSON.stringify({ type: 'content-changed', content: quill.getContents(), propositionId: propositionId }));
            }

        });

        socket.onmessage = function (event) {
            var data = JSON.parse(event.data);
            if (data.type === 'connected_users') {
                //Met à jour la liste des utilisateurs connectés
                updateConnectedUsers(data.data);
            }
            else if (data.type === 'content-changed') {
                // Appliquer les modifications reçues à l'éditeur Quill
                var content = data.content;
                console.log(content);
                quill.setContents(content);
                restoreCursorPosition();
            } else if (data.type === 'content') {
                //Applique le contenu des versions dans l'editeur Quill
                var content = data.content;
                quill.setContents(content);
            } else if (data.type === 'block-editor' && !admin) {
                quill.disable();
            } else if (data.type === 'unblock-editor' && !admin) {
                quill.enable();
            }

        };
        //Blockage d'edition

        if (admin) {
            document.querySelector('#block-editor').addEventListener('change', function () {
                if (this.checked) {
                    console.log("Blockage de l'editeur par " + username + " pour tous les utilisateurs");
                    // Envoyer une demande au serveur pour bloquer l'édition
                    socket.send(JSON.stringify({ type: 'block-editor',propositionId: propositionId }));
                } else {
                    console.log("Déblockage de l'editeur par " + username + " pour tous les utilisateurs");
                    // Envoyer une demande au serveur pour débloquer l'édition
                    socket.send(JSON.stringify({ type: 'unblock-editor',propositionId: propositionId }));

                }
            });
        }

        //Versions 
        // Récupère tous les éléments de la classe version-item
        var versions = document.querySelectorAll(".version-item");
        console.log("Nombre de versions trouvées : ", versions.length);
        // Parcourt chaque élément et ajoute un gestionnaire d'événements pour le clic
        versions.forEach(function (version) {

            version.addEventListener("click", function () {
                // Récupère les attributs data-content, data-date et data-user de l'élément cliqué
                var content = version.getAttribute("data-content");
                var date = version.getAttribute("data-date");
                var user = version.getAttribute("data-user");

                //Ajout du contenu de la version dans l'editeurs
                var delta = quill.clipboard.convert(content);
                quill.setContents(delta);
                socket.send(JSON.stringify({ type: 'content-changed', content: quill.getContents(), propositionId: propositionId }));
                console.log("Version du", date, "par", user);
            });
        });

        //mettreà jour le contenu lors du submit
        document.querySelector("form").addEventListener("submit", function (event) {
            var contenu = quill.root.innerHTML;
            /* var contenu = quill.getContents();
             var contenuJSON = JSON.stringify(contenu);*/
            console.log(contenu);
            /* var commentairesJSON = JSON.stringify(metaData);
             document.querySelector("#comments-container").value = commentairesJSON;*/
            document.querySelector("#contenu").value = contenu;
        });
    </script>
</body>

</html>