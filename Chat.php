<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $chatId = explode("=", $conn->httpRequest->getUri()->getQuery("chat_id"))[1];
        $this->clients->attach($conn, ['chat_id' => $chatId]);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $json = json_decode($msg, true);
        
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                if($this->clients[$client]['chat_id'] == $json['chat_id']) {
                    $client->send($json['mesazhi']);
                }
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}