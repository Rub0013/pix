<?php

namespace App\Classes\Sockets;

use App\Classes\Sockets\Base\BaseSocket;
use Ratchet\ConnectionInterface;
use App\Message;


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

        echo "New connection! ({$conn->resourceId})\n";
        if($conn->WebSocket->request->getQuery()['admin']){
            $socket_name = $this->adminId;
        }
        else{
            $socket_name = "{$conn->resourceId}@";
        }

        $this->clientIds[$socket_name] = $conn;
    }

    public function send_to($to,$msg) {
        if (array_key_exists($to, $this->clientIds)) $this->clientIds[$to]->send($msg);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        date_default_timezone_set('Asia/Yerevan');
        $data = json_decode($msg);
        $sendingData = [];
        $message = new Message;
        if(isset($data->message)){
            $sendingData['msg'] = $data->message;
            $message->message = $data->message;
        }
        if(isset($data->image)){
            $sendingData['img'] = $data->image;
            $message->image = $data->image;
        }
        if(isset($data->toId)){
            $toId = $data->toId;
            $to = "{$toId}@";
            $jsonData = json_encode($sendingData);
            $this->send_to($to,$jsonData);
            $message->connectionId = $toId;
            $message->seen = 1;
        }
        else{
            $sendingData['from_id'] = $from->resourceId;
            $jsonData = json_encode($sendingData);
            $this->send_to($this->adminId,$jsonData);
            $message->connectionId = $from->resourceId;
            $message->byClient = 1;
        }
        $message->save();
    }

    public function onClose(ConnectionInterface $conn) {
        if($conn->WebSocket->request->getQuery()['admin']){
            unset($this->clientIds[$this->adminId]);
        }
        else{
            unset($this->clientIds["$conn->resourceId@"]);
        }
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}