<?php

require __DIR__ . '/../../vendor/autoload.php';
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Collaboratif implements MessageComponentInterface
{
    protected $clients;
    protected $connectedUsers;
    protected $propositions;
    protected $content;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->connectedUsers = [];
        $this->propositions = []; // Tableau pour stocker les utilisateurs par proposition
        $this->content = [];
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Ajouter le nouvel utilisateur à la liste des utilisateurs connectés
        $this->clients->attach($conn);


    }

    public function onClose(ConnectionInterface $conn)
    {
        // Supprimer le client de la liste des clients
        $this->clients->detach($conn);
        // Supprimer l'utilisateur de l'espace de discussion de la proposition
        foreach ($this->propositions as &$users) {
            $index = array_search($conn->resourceId, $users);
            if ($index !== false) {
                echo ("Déconnexion de {$this->connectedUsers[$users[$index]]}\n\n");
                unset($users[$index]);
            }
        }
        // Diffuser la liste mise à jour des utilisateurs connectés pour chaque proposition
        foreach ($this->propositions as $propositionId => $users) {
            $this->broadcastConnectedUsers($propositionId);
        }
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);
        if (isset($data['type'])) {
            if ($data['type'] === 'connection_info') {
                $username = $data['username'];
                $propositionId = $data['propositionId'];
                // Stocker l'ID de la proposition avec l'ID de ressource du client
                if (!isset($this->propositions[$propositionId])) {
                    $this->propositions[$propositionId] = [];
                }
                $this->connectedUsers[$from->resourceId] = $username;

                $this->propositions[$propositionId][] = $from->resourceId;
                echo ("L'utilisateur {$this->connectedUsers[$from->resourceId]} s'est connecté à la proposition {$propositionId}\n\n");
                // Envoyer le contenu actuel de la proposition à l'utilisateur connecté
                if (isset($this->content[$propositionId])) {
                    $from->send(json_encode(['type' => 'content', 'content' => $this->content[$propositionId]]));
                }
                // Envoyer un message à tous les clients WebSocket pour informer de la nouvelle connexion
                $this->broadcastConnectedUsers($propositionId);

            } else if ($data['type'] === 'content-changed') {
                // Diffusion du contenu modifié uniquement aux utilisateurs connectés à la proposition
                $propositionId = $data['propositionId'];
                if (isset($this->propositions[$propositionId]) && in_array($from->resourceId, $this->propositions[$propositionId])) {
                    $content = $data['content'];
                    $this->content[$propositionId] = $content;
                    foreach ($this->clients as $client) {
                        if (in_array($client->resourceId, $this->propositions[$propositionId])) {
                            $client->send(json_encode(['type' => 'content-changed', 'content' => $content]));
                        }
                    }
                }
            } else if ($data['type'] === 'block-editor') {

                $propositionId = $data['propositionId'];
                $message = json_encode(['type' => 'block-editor']);
                foreach ($this->clients as $client) {
                    if (in_array($client->resourceId, $this->propositions[$propositionId])) {
                        $client->send($message);

                    }
                }

            } else if ($data['type'] === 'unblock-editor') {
                $propositionId = $data['propositionId'];
                $message = json_encode(['type' => 'unblock-editor']);
                foreach ($this->clients as $client) {
                    if (in_array($client->resourceId, $this->propositions[$propositionId])) {

                        $client->send($message);
                    }
                }
            }
        }
    }


    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }

    protected function broadcastConnectedUsers($propositionId)
    {
        $users = [];
        // Récupérer les noms des utilisateurs connectés à la proposition spécifiée
        foreach ($this->propositions[$propositionId] as $resourceId) {
            $users[] = $this->connectedUsers[$resourceId];
        }
        // Créer un message JSON contenant la liste des utilisateurs connectés
        $data = json_encode(['type' => 'connected_users', 'data' => $users]);
        // Envoyer le message à tous les clients WebSocket de cette proposition
        foreach ($this->clients as $client) {

            if (in_array($client->resourceId, $this->propositions[$propositionId])) {
                $client->send($data);
            }
        }
    }
}
