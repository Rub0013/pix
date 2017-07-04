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
use Illuminate\Database\QueryException;
use App\Branch;


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
        $devices = Device::select('id','model')->get();
        $services = Service::select('id','description')->get();
        $array = [
            'devices' => $devices,
            'services' => $services
        ];
        return view('auth.admin.admin-services_and_devices',$array);
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
                'validationError' => true,
                'success' => false,
                'message' => $validator->errors()->first()
            ));
        } else {
            try {
                $service = Service::firstOrCreate(['description' => $request['newService']]);
            } catch(QueryException $ex){
                return response()->json(array(
                    'error' => true,
                    'success' => false,
                    'message' => 'Проблемы с добавлением сервиса.'
                ));
            }
            if ($service->wasRecentlyCreated) {
                return response()->json(array(
                    'error' => false,
                    'success' => true,
                    'message' => 'Сервис успешно добавлен.',
                    'newService' => $service
                ));
            } else {
                return response()->json(array(
                    'validationError' => true,
                    'success' => false,
                    'message' => 'Сервис уже существует.'
                ));
            }
        }
    }

    public function updateService(Request $request) {
        $validator = Validator::make($request->all(), [
            'service' => 'required',
        ]);
        if ($validator->fails()) {
            $res = response()->json(array(
                'validationError' => true,
                'success' => false,
                'message' => $validator->errors()->first()
            ));
        } else {
            $service = Service::find($request['id']);
            $service->description = $request['service'];
            if($service->save()){
                $res =  response()->json(array(
                    'error' => false,
                    'success' => true,
                    'message' => 'Сервис успешно обновлен.'
                ));
            } else {
                $res =  response()->json(array(
                    'error' => true,
                    'success' => false,
                    'message' => 'Проблемы с обновлением сервиса.'
                ));
            }
        }
        return $res;
    }

    public function deleteService(Request $request) {
        $deleteService = Service::destroy($request['id']);
        if($deleteService) {
            return response()->json(array(
                'error' => false,
                'success' => true,
                'message' => 'Сервис успешно удален.'
            ));
        } else {
            return response()->json(array(
                'error' => true,
                'success' => false,
                'message' => 'Проблемы с удалением.'
            ));
        }
    }

    public function addDevice(Request $request) {
        $validator = Validator::make($request->all(), [
            'deviceModel' => 'required',
        ]);
        if ($validator->fails())
        {
            return response()->json(array(
                'validationError' => true,
                'success' => false,
                'message' => $validator->errors()->first()
            ));
        } else {
            try {
                $device = Device::firstOrCreate(['model' => $request['deviceModel']]);
            } catch(QueryException $ex){
                return response()->json(array(
                    'error' => true,
                    'success' => false,
                    'message' => 'Проблемы с добавлением устройства.'
                ));
            }
            if ($device->wasRecentlyCreated) {
                return response()->json(array(
                    'error' => false,
                    'success' => true,
                    'message' => 'Устройство успешно добавлено.',
                    'newDevice' => $device
                ));
            } else {
                return response()->json(array(
                    'validationError' => true,
                    'success' => false,
                    'message' => 'Устройство уже существует.'
                ));
            }
        }
    }

    public function updateDevice(Request $request) {
        $validator = Validator::make($request->all(), [
            'model' => 'required',
        ]);
        if ($validator->fails()) {
            $res = response()->json(array(
                'validationError' => true,
                'success' => false,
                'message' => $validator->errors()->first()
            ));
        } else {
            $device = Device::find($request['id']);
            $device->model = $request['model'];
            if($device->save()){
                $res =  response()->json(array(
                    'error' => false,
                    'success' => true,
                    'message' => 'Устройство успешно обновлено.'
                ));
            } else {
                $res =  response()->json(array(
                    'error' => true,
                    'success' => false,
                    'message' => 'Проблемы с обновлением устройства.'
                ));
            }
        }
        return $res;
    }

    public function deleteDevice(Request $request) {
        $deleteDevice = Device::destroy($request['id']);
        if($deleteDevice) {
            return response()->json(array(
                'error' => false,
                'success' => true,
                'message' => 'Устройство успешно удалено.'
            ));
        } else {
            return response()->json(array(
                'error' => true,
                'success' => false,
                'message' => 'Проблемы с удалением.'
            ));
        }
    }

    public function addServiceProduct(Request $request) {
        $validator = Validator::make($request->all(), [
            'device' => 'required|integer',
            'service' => 'required|integer',
            'price' => 'required|integer|min:0',
        ]);
        if ($validator->fails()) {
            return response()->json(array(
                'validationError' => true,
                'success' => false,
                'message' => $validator->errors()->first()
            ));
        } else {
            try {
                $serviceProduct = Price::firstOrCreate(
                    [
                        'device_id' => $request['device'],
                        'service_id' => $request['service'],
                    ],
                    ['price' => $request['price']]);
            } catch(QueryException $ex){
                return response()->json(array(
                    'error' => true,
                    'success' => false,
                    'message' => 'Проблемы с добавлением услуги.'
                ));
            }
            if ($serviceProduct->wasRecentlyCreated) {
                $service = Service::find($request['service']);
                return response()->json(array(
                    'error' => false,
                    'success' => true,
                    'message' => 'Услуга успешно добавлена.',
                    'newServiceProduct' => [
                        'description' => $service->description,
                        'id' => $serviceProduct->id,
                        'price' => $serviceProduct->price
                    ],
                ));
            } else {
                return response()->json(array(
                    'validationError' => true,
                    'success' => false,
                    'message' => 'Услуга уже существует.'
                ));
            }
        }
    }

    public function updateServiceProduct(Request $request) {
        $validator = Validator::make($request->all(), [
            'newPrice' => 'required|integer|min:0',
        ]);
        if ($validator->fails()) {
            $res = response()->json(array(
                'validationError' => true,
                'success' => false,
                'message' => $validator->errors()->first()
            ));
        } else {
            $newPrice = Price::find($request['id']);
            $newPrice->price = $request['newPrice'];
            if($newPrice->save()){
                $res =  response()->json(array(
                    'error' => false,
                    'success' => true,
                    'message' => 'Цена успешно обновлена.'
                ));
            } else {
                $res =  response()->json(array(
                    'error' => true,
                    'success' => false,
                    'message' => 'Проблемы с обновлением цены.'
                ));
            }
        }
        return $res;
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

    public function showMap() {
        $branches = Branch::select('title','address','lat','lng')->get();
        $array = [
            'branches' => $branches
        ];
        return view('auth.admin.admin-map', $array);
    }

    public function addBranch(Request $request) {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'title' => 'required|',
            'address' => 'required|'
        ]);
        if ($validator->fails()) {
            return response()->json(array(
                'validationError' => true,
                'success' => false,
                'message' => $validator->errors()->first()
            ));
        } else {
            try {
                $newBranch = Branch::firstOrCreate(
                    [
                        'lat' => $request['latitude'],
                        'lng' => $request['longitude'],
                        'title' => $request['title'],
                    ],
                    ['address' => $request['address']]);
            } catch(QueryException $ex){
                return response()->json(array(
                    'error' => true,
                    'success' => false,
                    'message' => 'Проблемы с добавлением филиала.'
                ));
            }
            if ($newBranch->wasRecentlyCreated) {
                return response()->json(array(
                    'error' => false,
                    'success' => true,
                    'message' => 'Филиал успешно добавлен.',
                    'newBranch' => $newBranch,
                ));
            } else {
                return response()->json(array(
                    'validationError' => true,
                    'success' => false,
                    'message' => 'Филиал уже существует.'
                ));
            }
        }
    }

}
