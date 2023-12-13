var quill;
var metaData = [];
$(document).ready(function() {
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

    ["clean"] // remove formatting button
  ];
  quill = new Quill("#editor", {
    modules: {
      toolbar: toolbarOptions
    },
    theme: "snow"
  });

});

$(document).on("click", "#comment-button", function() {
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
          background: "#fff72b"
        });
        drawComments(metaData);
      }
    } else {
      alert("Veuillez selectionner le texte que vous voulez commenter");
    }
  }
});

function drawComments(metaData) {
  var $commentContainer = $("#comments-container");
  var content = "";
  $.each(metaData, function(index, value) {
    content +=
      "<a class='comment-link' href='#' data-index='" +
      index +
      "'><li class='list-group-item'>" +
      value.comment +
      "</li></a>";
  });
  $commentContainer.html(content);
}

$(document).on('click','.comment-link',function () {
            var index = $(this).data('index');
            console.log("comment link called",index);
            var data = metaData[index];
            quill.setSelection(data.range.index, data.range.length);
        });

document.querySelector('form').addEventListener('submit', function(event) {
    
    var contenu = quill.root.innerHTML;
        
    var commentairesJSON = JSON.stringify(metaData);
    document.querySelector('#comments-container').value = commentairesJSON;
    document.querySelector('#contenu').value = contenu;
});
