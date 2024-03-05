<?php
session_start();
include('base.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: connexion.php"); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}

// Vérifier si l'ID de la proposition est défini dans l'URL
if (!isset($_GET["id_pro"])) {
    header("location: espace_prive.php"); // Rediriger vers la page d'accueil si l'ID de la proposition n'est pas défini
    exit();
}

$id_proposition = $_GET["id_pro"];

// Récupérer les informations de la proposition
$sql_proposition = "SELECT * FROM t_proposition_pro WHERE id_pro = $id_proposition";
$result_proposition = $conn->query($sql_proposition);

if ($result_proposition->num_rows != 1) {
    header("location: liste_propositions.php");
    exit();
}
/*
// Vérifier si la proposition est verrouillée
$sql_check_lock = "SELECT verrou FROM t_proposition_pro WHERE id_pro = $id_proposition FOR UPDATE";
$result_check_lock = $conn->query($sql_check_lock);

if ($result_check_lock->num_rows == 1) {
    $row = $result_check_lock->fetch_assoc();
    if ($row["verrou"] == 1) {
        // La proposition est déjà verrouillée, redirige l'utilisateur 
        header("location: liste_propositions.php?error=PropositionLocked");
        exit();
    } else {
        // Verrouiller la proposition
        $sql_lock_proposition = "UPDATE t_proposition_pro SET verrou = 1 WHERE id_pro = $id_proposition";
        $conn->query($sql_lock_proposition);
        
    }
} else {
    // Proposition non trouvée, redirige l'utilisateur
    header("location: liste_propositions.php?error=PropositionNotFound");
    exit();
}*/
$row_proposition = $result_proposition->fetch_assoc();
$titre = $row_proposition["titre_pro"];
$contenu = $row_proposition["contenu_pro"];

if (isset($_POST["enregistrer"])) {

    $nouveau_titre = $_POST["titre"];
    $nouveau_contenu = $_POST["contenu"];

    // Récupérer la version précédente de la proposition depuis la base de données
    $sql_version_precedente = "SELECT contenu_pro FROM t_proposition_pro WHERE id_pro = $id_proposition";
    $result_version_precedente = $conn->query($sql_version_precedente);
    $row_version_precedente = $result_version_precedente->fetch_assoc();
    $ancien_contenu = $row_version_precedente["contenu_pro"];

    // Comparer le contenu précédent avec le nouveau contenu
    if ($ancien_contenu != $nouveau_contenu) { //Si il y a une modification

        // Vérifier s'il existe déjà une entrée pour cette proposition dans t_modification_mod
        $sql_verifier_modification = "SELECT id_mod, id_uti FROM t_modification_mod WHERE id_pro = $id_proposition ORDER BY id_mod DESC LIMIT 1";
        $result_verifier_modification = $conn->query($sql_verifier_modification);

        if ($result_verifier_modification->num_rows > 0) {
            $row_verifier_modification = $result_verifier_modification->fetch_assoc();
            $derniere_modification_id = $row_verifier_modification["id_mod"];
            $dernier_utilisateur_modification = $row_verifier_modification["id_uti"];

            if ($dernier_utilisateur_modification == $_SESSION["user_id"]) {
                // Si le dernier utilisateur est le même, mettre à jour la dernière entrée
                $update_modification = "UPDATE t_modification_mod SET contenumodif_com = '$nouveau_contenu', datecrea_mod = NOW() WHERE id_mod = $derniere_modification_id";
                $conn->query($update_modification);
            } else {
                // Sinon, créer une nouvelle entrée pour la nouvelle version
                $insert_modification = "INSERT INTO t_modification_mod (contenumodif_com, datecrea_mod, id_pro, id_uti) VALUES ('$nouveau_contenu', NOW(), $id_proposition, {$_SESSION['user_id']})";
                $conn->query($insert_modification);
            }
        } else {

            // Si aucune entrée n'existe, créer une nouvelle entrée pour la nouvelle version
            $insert_modification = "INSERT INTO t_modification_mod (contenumodif_com, datecrea_mod, id_pro, id_uti) VALUES ('$nouveau_contenu', NOW(), $id_proposition, {$_SESSION['user_id']})";

            $conn->query($insert_modification);
        }
    }

    // Mettre à jour la proposition dans la table t_proposition_pro
    $update_proposition = "UPDATE t_proposition_pro SET titre_pro = '$nouveau_titre', contenu_pro = '$nouveau_contenu' WHERE id_pro = $id_proposition";
    $conn->query($update_proposition);
    // Mettre à jour le verrou de la proposition dans la base de données à 0 pour la déverrouiller
    $sql_unlock_proposition = "UPDATE t_proposition_pro SET verrou = 0 WHERE id_pro = $id_proposition";
    $conn->query($sql_unlock_proposition);

    header("location: liste_propositions.php");
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Modifier une proposition</title>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.socket.io/3.1.3/socket.io.min.js"></script>

</head>

<body>
    <h1>Bonjour
        <?php echo $_SESSION["username"] ?>
    </h1>
    <h1>Modifier une proposition</h1>

    <?php
    // Récupérer l'ID de l'administrateur du groupe associé à cette proposition
    $sql_admin_group = "SELECT admin FROM t_groupe_grp WHERE id_grp = (SELECT id_grp FROM t_proposition_pro WHERE id_pro = $id_proposition)";
    $result_admin_group = $conn->query($sql_admin_group);

    if ($result_admin_group->num_rows == 1) {
        $row_admin_group = $result_admin_group->fetch_assoc();
        $group_admin_id = $row_admin_group['admin'];

        // Vérifier si l'utilisateur actuel est l'administrateur du groupe
        if ($_SESSION['username'] == $group_admin_id) {
            // Afficher le bouton de blocage de l'éditeur Quill uniquement pour l'administrateur du groupe
            echo '<input type="checkbox" id="block-editor" name="block-editor">
            <label for="block-editor">Bloquer l\'édition pour les autres utilisateurs</label>';
        }
    }

    ?>
    <h3>Utilisateurs connectés :</h3>
    <ul id="connectedUsers"></ul>
    <form method="POST">
        <label for="titre">Titre de la proposition:</label>
        <input type="text" name="titre" id="titre" value="<?php echo $titre; ?>" required>
        <br>
        <label for="contenu">Contenu de la proposition:</label>
        <button type="button" id="comment-button" style="color:red">Comment</button>


        <div id="editor">
            <?php echo $contenu ?>
        </div>
        <input type="hidden" name="contenu" id="contenu">
        Comments
        <ul class="list-group" id="comments-container">
            <?php
            // Récupérer les commentaires de la proposition depuis la base de données
            $sql_commentaires = "SELECT * FROM t_commentaire_com WHERE id_pro = $id_proposition";
            $result_commentaires = $conn->query($sql_commentaires);

            if ($result_commentaires->num_rows > 0) {
                while ($row_commentaire = $result_commentaires->fetch_assoc()) {
                    $contenu_com = $row_commentaire["contenu_com"];
                    $id_uti = $row_commentaire["id_uti"];

                    // Récupérer le nom de l'utilisateur associé au commentaire
                    $sql_utilisateur = "SELECT nom_uti FROM t_utilisateur_uti WHERE id_uti = $id_uti";
                    $result_utilisateur = $conn->query($sql_utilisateur);
                    $row_utilisateur = $result_utilisateur->fetch_assoc();
                    $nom_utilisateur = $row_utilisateur["nom_uti"];

                    // Ajouter le commentaire à la liste
                    echo "<li class='list-group-item'>$contenu_com - Posté par: $nom_utilisateur</li>";
                }
            } else {
                echo "<li class='list-group-item'>Aucun commentaire disponible.</li>";
            }
            ?>
        </ul>
        <button type="submit" name="enregistrer" id="enregistrer">Enregistrer les changements</button>
        <h2>Versions précédentes</h2>
        <ul id="versions-container">
            <div id="back-button-container"></div>
            <?php
            $sql_versions = "SELECT m.contenumodif_com, m.datecrea_mod, u.nom_uti 
               FROM t_modification_mod m
               INNER JOIN t_utilisateur_uti u ON m.id_uti = u.id_uti
               WHERE m.id_pro = $id_proposition
               ORDER BY m.datecrea_mod DESC"; // Trie du plus récent au plus ancien
            
            $result_versions = $conn->query($sql_versions);

            if ($result_versions->num_rows > 0) {
                echo "<ul id='versions-container'>";
                while ($row_version = $result_versions->fetch_assoc()) {
                    $contenu_version = $row_version["contenumodif_com"];
                    $date_version = $row_version["datecrea_mod"];
                    $nom_utilisateur = $row_version["nom_uti"];

                    echo "<li class='version-item' data-content='$contenu_version' data-date='$date_version' data-user='$nom_utilisateur'>
               Version du $date_version par $nom_utilisateur
               </li>";

                }
                echo "</ul>";
            } else {
                echo "<p>Aucune version précédente disponible.</p>";
            }
            ?>
        </ul>

    </form>


    <script>
        //Creation de la socket 
        var propositionId = "<?php echo $id_proposition; ?>";
        var username = "<?php echo $_SESSION['username']; ?>";
        var admin = "<?php echo $group_admin_id; ?>";
        const socket = io('http://localhost:3001');

        //Lors de la connection, on envoit le pseudo de l'utilisateur au serveur et la proposition qui lui appartient
        socket.on('connect', () => {
            socket.emit("join-proposition", propositionId);
            socket.emit("user-connected", username, propositionId);
        });

        //Affichage à tous les utilisateurs de la connexion d'un utilisateur
        socket.on("user-connected", username => {
            console.log(username + " s'est connecté(e)");
        });

        //Affichage à tous les utilisateurs de la déconnexion d'un utilisateur
        socket.on("user-disconnected", username => {
            console.log(username + " s'est déconnecté(e)");
        });
        //Affichage de la liste de tous les utilisateurs connectés
        socket.on("user-list", userList => {
            console.log("Utilisateurs connectés :", userList);
            var connectedUsersList = document.getElementById("connectedUsers");
            connectedUsersList.innerHTML = "";
            // Parcourez la liste des utilisateurs et mettez à jour l'affichage
            userList.forEach(user => {
                var listItem = document.createElement("li");
                listItem.textContent = user;
                connectedUsersList.appendChild(listItem);
            });
        });

        //Ajout de l'editeur quill
        var quill;
        var metaData = [];
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

        quill = new Quill("#editor", {
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

        //Envoie des modification de l'editeur au serveur
        quill.on("text-change", function (delta, oldDelta, source) {
            if (source === "user") {
                // Envoyer les changements via Socket.io
                socket.emit("content-changed", quill.getContents());
            }
        });

        //Affichage du contenu modifié à tous les utilisateurs
        socket.on("content-changed", function (contents) {
            quill.setContents(contents);
        });

        document.querySelector("form").addEventListener("submit", function (event) {
            var contenu = quill.root.innerHTML;
            /* var contenu = quill.getContents();
             var contenuJSON = JSON.stringify(contenu);*/
            console.log(contenu);
            /* var commentairesJSON = JSON.stringify(metaData);
             document.querySelector("#comments-container").value = commentairesJSON;*/
            document.querySelector("#contenu").value = contenu;
        });

        //Gestion de la checkbox pour bloquer l'edition des utilisateurs
        if (username == admin) {
            document.querySelector('#block-editor').addEventListener('change', function () {
                if (this.checked) {
                    console.log("Blockage de l'editeur par " + username + " pour tous les utilisateurs");
                    // Envoyer une demande au serveur pour bloquer l'édition
                    socket.emit('block-editor', propositionId);
                } else {
                    console.log("Déblockage de l'editeur par " + username + " pour tous les utilisateurs");
                    // Envoyer une demande au serveur pour débloquer l'édition
                    socket.emit('unblock-editor', propositionId);

                }
            });
        }

        // Gérer la réponse du serveur pour bloquer l'édition côté client
        socket.on('editor-blocked', function () {
            // Empêcher l'utilisateur actuel d'éditer le contenu
            if (username != admin) {
                console.log("Blockage de l'editeur par " + admin);
                quill.disable();
            }

        });

        // Gérer la réponse du serveur pour débloquer l'édition côté client
        socket.on('editor-unblocked', function () {
            console.log("Déblockage de l'editeur par " + admin);
            // Autoriser l'utilisateur actuel à éditer le contenu
            quill.enable();
        });




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

                console.log("Version du", date, "par", user);
            });
        });

        /*Commentaires */
        function sendMetaData(metaData) {
            socket.emit("metadata-changed", metaData);
        }
        socket.on("metadata-changed", function (metaData) {
            drawComments(metaData);
        });

        $(document).on("click", "#comment-button", function () {
            var prompt = window.prompt("Insérer un commentaire", "");
            var txt;
            if (prompt == null || prompt == "") {
                txt = "User cancelled the prompt.";
            } else {
                var range = quill.getSelection();
                if (range) {
                    if (range.length == 0) {
                        alert("Selectionner le texte à commenter", range.index);
                    } else {
                        var text = quill.getText(range.index, range.length);
                        console.log("l'utilisateur a surligné: ", text);
                        metaData.push({ range: range, comment: prompt });
                        quill.formatText(range.index, range.length, {
                            background: "#fff72b"
                        });
                        drawComments(metaData);
                        sendMetaData(metaData);

                    }
                } else {
                    alert("User cursor is not in editor");
                }
            }
        });
        function drawComments(metaData) {
            var $commentContainer = $("#comments-container");
            var content = "";
            $.each(metaData, function (index, value) {
                content +=
                    "<a class='comment-link' href='#' data-index='" +
                    index +
                    "'><li class='list-group-item'>" +
                    value.comment +
                    "</li></a>";
            });
            $commentContainer.html(content);
        }



        $(document).on('click', '.comment-link', function () {
            var index = $(this).data('index');
            console.log("comment link called", index);
            var data = metaData[index];
            quill.setSelection(data.range.index, data.range.length);
        });

    </script>
</body>

</html>