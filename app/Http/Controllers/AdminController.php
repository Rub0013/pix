<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use DB;


class AdminController extends Controller
{
    public function panel(){
        return view('auth.admin.admin-panel');
    }

    public function profile(){
        return view('auth.admin.admin-profile');
    }

    public function chat(){
        $allMessages = array();
        $allConnections = Message::select('connectionId','byClient','message','image','created_at')->orderBy('connectionId')
            ->get();;
        $sameConn = false;
        foreach($allConnections as $connection){
            if($sameConn != $connection->connectionId){
                $sameConn = $connection->connectionId;
                $allMessages[$connection->connectionId] = array();
            }
            array_push($allMessages[$connection->connectionId], $connection);
        }
        $array = array(
            'chats' => $allMessages,
        );
        return view('auth.admin.admin-chat',$array);
    }
}
