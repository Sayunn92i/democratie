<?php

use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Http\HttpServer;

require __DIR__ . '/Collaboratif.php';
require __DIR__ . '/../../vendor/autoload.php';


$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Collaboratif())
            ),
    8080
    
);
try {
    echo "serveur lancé sur le port 8080\n";
    $server->run();
    
} catch (Exception $e) {
    echo "Erreur lors de l'exécution du serveur : " . $e->getMessage() . "\n";
}
echo "Serveur arrêté\n";