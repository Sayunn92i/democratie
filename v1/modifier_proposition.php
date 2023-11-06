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

$row_proposition = $result_proposition->fetch_assoc();
$titre = $row_proposition["titre_pro"];
$contenu = $row_proposition["contenu_pro"];

if (isset($_POST["enregistrer"])) {
    $nouveau_titre = $_POST["titre"];
    $nouveau_contenu = $_POST["contenu"];

    // Mettre à jour la proposition dans la base de données
    $update_proposition = "UPDATE t_proposition_pro SET titre_pro = '$nouveau_titre', contenu_pro = '$nouveau_contenu' WHERE id_pro = $id_proposition";
    $conn->query($update_proposition);
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
</head>
<body>
    <h1>Bonjour <?php echo $_SESSION["username"]?></h1>
    <h1>Modifier une proposition</h1>
    <form method="POST">
        <label for="titre">Titre de la proposition:</label>
        <input type="text" name="titre" id="titre" value="<?php echo $titre; ?>" required><br>

        <label for="contenu">Contenu de la proposition:</label>
        <div id="editor" style="height: 300px;"><?php echo $contenu; ?></div>
        <input type="hidden" name="contenu" id="contenu">
        <button type="submit" name="enregistrer" id="enregistrer">Enregistrer les changements</button>
    </form>

    <script>
var toolbarOptions = [
  ['bold', 'italic', 'underline', 'strike'],        // toggled buttons


  [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
                                        
];
var quill = new Quill('#editor', {
  modules: {
    history: {
      delay: 2000,
      maxStack: 500,
      userOnly: true
    },
    toolbar: toolbarOptions
  },
  theme: 'snow'
});


        document.querySelector('form').addEventListener('submit', function(event) {
            var contenu = quill.root.innerHTML;
            document.querySelector('#contenu').value = contenu;
        });
    </script>
</body>
</html>
