<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\Device;
use App\Service;
use App\Price;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


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

    public function servicesAndDevices(){
        return view('auth.admin.admin-services_and_devices');
    }

    public function prices() {
        $services = Service::select('id','description')->get();
        $devices = Device::with('prices.service')->select('*')->get();
        $array = array(
            'devices' => $devices,
            'services' => $services,
        );
        return view('auth.admin.admin-prices', $array);
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

    public function addService(Request $request) {
        $validator = Validator::make($request->all(), [
            'newService' => 'required',
        ]);
        if ($validator->fails())
        {
            return response()->json(array(
                'error' => $validator->getMessageBag()->toArray(),
                'success' => false
            ));
        } else {
            $service = Service::firstOrCreate(['description' => $request['newService']]);
            if ($service) {
                return response()->json(array(
                    'error' => false,
                    'success' => true,
                    'message' => 'Сервис успешно добавлен.'
                ));
            } else {
                return response()->json(array(
                    'error' => true,
                    'success' => false,
                    'message' => 'Проблемы с добавлением сервиса.'
                ));
            }
        }
    }

    public function addDevice(Request $request) {
        $validator = Validator::make($request->all(), [
            'deviceModel' => 'required',
        ]);
        if ($validator->fails())
        {
            return response()->json(array(
                'error' => $validator->getMessageBag()->toArray(),
                'success' => false
            ));
        } else {
            $device = Device::firstOrCreate(['model' => $request['deviceModel']]);
            if ($device) {
                return response()->json(array(
                    'error' => false,
                    'success' => true,
                    'message' => 'Устройство успешно добавлено.'
                ));
            } else {
                return response()->json(array(
                    'error' => true,
                    'success' => false,
                    'message' => 'Проблемы с добавлением устройства.'
                ));
            }
        }
    }

    public function addServiceProduct(Request $request) {
        $validator = Validator::make($request->all(), [
            'device' => 'required|integer',
            'service' => 'required|integer',
            'price' => 'required|integer|min:0',
        ]);
        if ($validator->fails())
        {
            return response()->json(array(
                'error' => $validator->getMessageBag()->toArray(),
                'success' => false
            ));
        } else {
            $serviceProduct = Price::firstOrCreate(
                [
                    'device_id' => $request['device'],
                    'service_id' => $request['service'],
                ],
                ['price' => $request['price']]
            );
            if ($serviceProduct) {
                return response()->json(array(
                    'error' => false,
                    'success' => true,
                    'message' => 'Услуга успешно добавлена.'
                ));
            } else {
                return response()->json(array(
                    'error' => true,
                    'success' => false,
                    'message' => 'Проблемы с добавлением услуги.'
                ));
            }
        }
    }

    public function delServiceProduct(Request $request) {
        $deleteServiceProduct = Price::destroy($request['productId']);
        if($deleteServiceProduct) {
            return response()->json(array(
                'error' => false,
                'success' => true,
                'message' => 'Услуга успешно удалена.'
            ));
        } else {
            return response()->json(array(
                'error' => true,
                'success' => false,
                'message' => 'Проблемы с удалением.'
            ));
        }
    }

}
