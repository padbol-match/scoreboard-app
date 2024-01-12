<?php

namespace App\Services\WebSocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class WebSocketServer implements MessageComponentInterface {

  protected $clients;

  public function __construct() {
    $this->clients = new \SplObjectStorage;
  }

    public function onOpen(ConnectionInterface $conn) {
      $this->clients->attach($conn);
      $numberOfClients = count($this->clients);
      dump("Open connection: $conn->resourceId. Clients: $numberOfClients");
    }

    public function onMessage(ConnectionInterface $from, $msg) {
      dump("Message from: $from->resourceId");

      foreach ($this->clients as $client) {
        if ($from != $client) {
            dump("Send message to $client->resourceId" );
            $client->send($msg);
        }
      }
    }

    public function onClose(ConnectionInterface $conn) {
      $numberOfClients = count($this->clients);
      dump("Connection closed by: $conn->resourceId. Still $numberOfClients connected." );
      $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
      dump("Connection error: $conn->resourceId" );
      $conn->close();
    }
}