var quill;
var metaData = [];
var contenuProposition;
var customSizes = [
  { label: "10px", value: "10px" },
  { label: "12px", value: "12px" },
  { label: "14px", value: "14px" },
  { label: "16px", value: "16px" },
  { label: "18px", value: "18px" },
  { label: "20px", value: "20px" },
  { label: "24px", value: "24px" },
  { label: "28px", value: "28px" },
  { label: "32px", value: "32px" },
  { label: "36px", value: "36px" },
  { label: "40px", value: "40px" },
  { label: "48px", value: "48px" },
  { label: "56px", value: "56px" },
  { label: "64px", value: "64px" },
  { label: "72px", value: "72px" },
  { label: "80px", value: "80px" },
  { label: "90px", value: "90px" },
];
  var toolbarOptions = [
    ["bold", "italic", "underline", "strike"], // toggled buttons
    // ['blockquote'],
    [{ list: "ordered" }, { list: "bullet" }],
    [{ script: "sub" }, { script: "super" }], // superscript/subscript
    [{ indent: "-1" }, { indent: "+1" }], // outdent/indent
    [{ direction: "rtl" }], // text direction

    [{ size: ["small", false, "large", "huge"] }], // custom dropdown
    [{ header: [1, 2, 3, 4, 5, 6, false] }],

    [{ color: [] }, { background: [] }], // dropdown with defaults from theme
    [{ font: [] }],
    [{ align: [] }],

    ["clean"], // remove formatting button
  ];
  quill = new Quill("#editor", {
    modules: {
      history: {
        delay: 2000,
        maxStack: 500,
        userOnly: true,
      },
      toolbar: toolbarOptions,
    },
    theme: "snow",
  });

$(document).on("click", "#comment-button", function () {
  var prompt = window.prompt("Please enter Comment", "");
  var txt;
  if (prompt == null || prompt == "") {
    txt = "User cancelled the prompt.";
  } else {
    var range = quill.getSelection();
    if (range) {
      if (range.length == 0) {
        alert("Ajouter du texte", range.index);
      } else {
        var text = quill.getText(range.index, range.length);
        console.log("L'utilisateur a surligné: ", text);
        metaData.push({ range: range, comment: prompt });
        quill.formatText(range.index, range.length, {
          background: "#fff72b",
        });
        drawComments(metaData);
      }
    } else {
      alert("Veuillez selectionner le texte que vous voulez commenter");
    }
  }
});

function deleteComment(index) {
  var data = metaData[index];
  quill.formatText(data.range.index, data.range.length, {
    background: "#ffffff", // Mettre le surlignage en blanc
  });
  metaData.splice(index, 1); // Supprimer le commentaire du tableau metaData
  drawComments(metaData); // Rafraîchir l'affichage des commentaires
}
$(document).on("click", ".delete-comment", function () {
  var index = $(this).data("index");
  deleteComment(index);
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
      " - Posted by: " +
      username +
      "<button class='delete-comment' data-index='" +
      index +
      "'>Supprimer</button></li></a>";
  });
  $commentContainer.html(content);
}

$(document).on("click", ".comment-link", function () {
  var index = $(this).data("index");
  console.log("comment link called", index);
  var data = metaData[index];
  quill.setSelection(data.range.index, data.range.length);
});

document.querySelector("form").addEventListener("submit", function (event) {
  var contenu = quill.root.innerHTML;
 /* var contenu = quill.getContents();
  var contenuJSON = JSON.stringify(contenu);*/

  var commentairesJSON = JSON.stringify(metaData);
  document.querySelector("#comments-container").value = commentairesJSON;
  document.querySelector("#contenu").value = contenu;
});

// Récupère tous les éléments de la classe version-item
var versions = document.querySelectorAll(".version-item");
console.log("Nombre de versions trouvées : ", versions.length);
// Parcourt chaque élément et ajoute un gestionnaire d'événements pour le clic
versions.forEach(function (version) {
  console.log("Version :", version);
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
