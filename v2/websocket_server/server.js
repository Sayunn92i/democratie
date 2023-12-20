const io = require('socket.io')(3001, {
  cors: {
    methods: ["GET", "POST"],
  },

})
// Liste des espaces de discussion par proposition
let propositionSpaces = {};
let connectedUsers = []; // Tableau pour stocker les pseudos des utilisateurs connectés
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
  /*
  socket.on("user-connected", username => {
    if (!connectedUsers.includes(username)) {
      socket.username = username;
      connectedUsers.push(username); // Ajouter l'utilisateur à la liste des connectés

      console.log(username + " s'est connecté(e)");
      // Ajouter l'utilisateur à la liste des connectés
      io.emit("user-connected", username); // Émettre à tous les utilisateurs le pseudo de l'utilisateur connecté
    }
    io.emit("user-list", connectedUsers); // Émettre la liste des utilisateurs connectés à tous les utilisateurs

  });

  socket.on("disconnect",() => {
    console.log(socket.username + " Client disconnected");
    if (socket.username) {
      connectedUsers = connectedUsers.filter(user => user !== socket.username);
      io.emit("user-list", connectedUsers);
      io.emit("user-disconnected", socket.username);
    }
  });*/
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
});



