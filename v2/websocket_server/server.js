const WebSocket = require('ws');
const wss = new WebSocket.Server({ port: 8080 });

wss.on('connection', function connection(ws) {
  // Lorsqu'une nouvelle connexion WebSocket est établie
  console.log('Nouvelle connexion établie');

  // Gérer les messages entrants (vous pouvez envoyer des événements spécifiques aux utilisateurs)
  ws.on('message', function incoming(message) {
    console.log('Message reçu:', message);
  });

  const connectedUsers = new Set(); // Pour stocker les utilisateurs connectés

wss.on('connection', function connection(ws) {
  // Nouvelle connexion WebSocket
  connectedUsers.add(ws); // Ajouter l'utilisateur à la liste des connexions

  // Envoi du nombre d'utilisateurs connectés à tous les clients
  broadcastConnectedUsers();

  ws.on('close', function() {
    // Lorsqu'un utilisateur se déconnecte
    connectedUsers.delete(ws); // Supprimer l'utilisateur de la liste des connexions

    // Envoi du nombre d'utilisateurs connectés mis à jour à tous les clients
    broadcastConnectedUsers();
  });
});

function broadcastConnectedUsers() {
  // Envoi du nombre total d'utilisateurs connectés à tous les clients
  wss.clients.forEach(function each(client) {
    client.send(`Nombre total d'utilisateurs connectés : ${connectedUsers.size}`);
  });
}
});

