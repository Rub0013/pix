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
        $allConnections = Message::select('connectionId')->distinct()->get();
        foreach($allConnections as $connection){
            $conversation = Message::select('byClient','message','image','created_at')
                ->where('connectionId', $connection->connectionId)
                ->orderBy('created_at','asc')
                ->get();
            $allMessages[$connection->connectionId]=$conversation;
        }
        $array = array(
            'chats' => $allMessages,
        );
        return view('auth.admin.admin-chat',$array);
    }
}
