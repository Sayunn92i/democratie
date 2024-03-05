const io = require('socket.io')(3001, {
  cors: {
    methods: ["GET", "POST"],
  },

})
// Liste des espaces de discussion par proposition
let propositionSpaces = {};
io.on("connection", socket => {

  socket.on("user-connected", (username, propositionId) => {
    if (!propositionSpaces[propositionId]) {
      propositionSpaces[propositionId] = [];
    }

    if (!propositionSpaces[propositionId].includes(username)) {
      socket.username = username;
      socket.propositionId = propositionId;
      propositionSpaces[propositionId].push(username);


      console.log(username + " s'est connecté(e) à la proposition " + propositionId);
      // Émettre à tous les utilisateurs de la même proposition
      io.in(propositionId).emit("user-connected", username);

      // Émettre la liste des utilisateurs de la même proposition à tous les utilisateurs
      io.in(propositionId).emit("user-list", propositionSpaces[propositionId]);
    }
  });
  socket.on("disconnect", () => {
    if (socket.username && socket.propositionId && propositionSpaces[socket.propositionId]) {
      console.log(socket.username + " s'est déconnecté(e) de la proposition " + socket.propositionId);
      propositionSpaces[socket.propositionId] = propositionSpaces[socket.propositionId].filter(user => user !== socket.username);
      io.to(socket.propositionId).emit("user-list", propositionSpaces[socket.propositionId]);
      io.to(socket.propositionId).emit("user-disconnected", socket.username);
    }
  });


  socket.on("content-changed", function (contents) {
    socket.broadcast.emit("content-changed", contents);
  });

  socket.on("join-proposition", propositionId => {
    socket.join(propositionId); // Joindre la salle spécifique à la proposition
  });

  socket.on('block-editor', function (propositionId) {
    console.log("Blockage de l'éditeur de la proposition "+ propositionId);
    // Envoyez un signal de blocage d'édition à tous les clients connectés à cette proposition
    io.to(propositionId).emit('editor-blocked'); // Utilisez io pour émettre à tous les clients, ou spécifiez le groupe concerné
  });

  // Lorsque vous recevez une demande pour débloquer l'édition
  socket.on('unblock-editor', function (propositionId) {
    console.log("Délockage de l'éditeur de la proposition "+ propositionId);
    // Envoyez un signal de déblocage d'édition à tous les clients connectés à cette proposition
    io.to(propositionId).emit('editor-unblocked'); // Utilisez io pour émettre à tous les clients, ou spécifiez le groupe concerné
  });

  // Lorsqu'un commentaire est ajouté dans la fonction associée
  socket.on("metadata-changed", function(updatedMetaData) {
    metaData = updatedMetaData;
    // Mettre à jour l'affichage des commentaires côté client
    io.to(socket.propositionId).emit("metadata-changed", metaData);
});
});



