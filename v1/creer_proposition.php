<?php session_start();

// Vérification de la session
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: connexion.php"); // Redirige l'utilisateur vers la page de connexion s'il n'est pas connecté
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une proposition</title>
    
    <link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="//cdn.quilljs.com/1.3.6/quill.js"></script>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <?php include('header_backend.php');?>
    
    <h1>Créer une proposition</h1>
    <div class="container-editor">
    
    <form action="traitement_proposition.php" method="post">
    <input type="hidden" name="token" value="<?php echo $_SESSION["token"]; ?>">
    <label for="titre">Titre de la proposition :</label>
    <input type="text" id="titre" name="titre" required><br><br>

    <label for="contenu">Contenu de la proposition :</label>
    <div id="editor"></div><br><br>

    <input type="submit" id="saveButton" value="Créer Proposition">
    </form>

    
        
    <script>
    const socket = new WebSocket('ws://localhost:3000');

    const quill = new Quill('#editor', {
        theme: 'snow'
    });

    socket.addEventListener('message', (event) => {
        quill.root.innerHTML = event.data;
    });

    document.getElementById('saveButton').addEventListener('click', () => {
        const content = quill.root.innerHTML;
        socket.send(content);
    });

    </script>
    </div>
</body>
</html>
*/