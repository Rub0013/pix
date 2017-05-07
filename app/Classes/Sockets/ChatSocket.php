<?php

namespace App\Classes\Sockets;

use App\Classes\Sockets\Base\BaseSocket;
use Ratchet\ConnectionInterface;


class ChatSocket extends BaseSocket
{
    protected $clients;
    protected $clientIds;
    protected $adminId = "admin@";

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->clientIds = array();
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        echo "New connection! ({$conn->resourceId})\n";
        if($conn->WebSocket->request->getQuery()['admin']){
            $socket_name = $this->adminId;
        }
        else{
            $socket_name = "{$conn->resourceId}@";
        }
//        $this->clients->attach($conn,$socket_name);
        $this->clientIds[$socket_name] = $conn;
    }

    public function send_to($to,$msg) {
        if (array_key_exists($to, $this->clientIds)) $this->clientIds[$to]->send($msg);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg);
        $message = $data->message;
        if(isset($data->toId)){
            $toId = $data->toId;
            $to = "{$toId}@";
            $data = [
                'msg' => $message
            ];
            $jsonData = json_encode($data);
            $this->send_to($to,$jsonData);
        }
        else{
            $data = [
                'from_id' => $from->resourceId,
                'msg' => $message
            ];
            $jsonData = json_encode($data);
            $this->send_to($this->adminId,$jsonData);
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        if($conn->WebSocket->request->getQuery()['admin']){
            unset($this->clientIds[$this->adminId]);
        }
        else{
            unset($this->clientIds["$conn->resourceId@"]);
        }
//        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}