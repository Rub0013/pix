<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use DB;
use Illuminate\Support\Facades\Storage;


class AdminController extends Controller
{
    public function unseenMessages(){
        $notes = Message::select('connectionId',DB::raw('count(*) as total'))
            ->where('seen',0)
            ->groupBy('connectionId')
            ->get();
        return $notes;
    }

    public function updateSeen($id){
        Message::where('connectionId',$id)
            ->where('seen',0)
            ->update(['seen' => 1]);
    }

    public function panel(){
        return view('auth.admin.admin-panel');
    }

    public function profile(){
        return view('auth.admin.admin-profile');
    }

    public function chat(){
        $allMessages = array();
        $allConnections = Message::select('connectionId','byClient','message','image','created_at')->orderBy('connectionId')
            ->get();
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

    public function openConversation(Request $request){
        $id = $request['id'];
        $this->updateSeen($id);
        $unseen = $this->unseenMessages();
        return response()->json([
            'data' => [
                'unseen' => $unseen
            ],
            'error'=>false,
            'success'=>true,
        ]);
    }

    public function getNotes(){
        $unseen = $this->unseenMessages();
        return response()->json([
            'data' => [
                'unseen' => $unseen
            ],
            'error'=>false,
            'success'=>true,
        ]);
    }

    public function deletePanel(Request $request){
        $id = $request['id'];
        $images = Message::select('image')->where('connectionId', $id)->whereNotNull('image')->get();
        if(count($images)>0)
        {
            foreach ($images as $image){
                Storage::disk('upload')->delete($image['image']);
            }
        }
        Message::where('connectionId', $id)->delete();
        return response()->json([
            'error' => false,
            'success' => true,
        ]);
    }

}
