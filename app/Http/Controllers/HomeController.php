<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Offer;
use App\Contact;
use App\Device;
use Illuminate\Http\Request;
use Cornford\Googlmapper\Facades\MapperFacade as Mapper;

class HomeController extends MainController
{
    public function show(){
        $branches = Branch::select('title','address','lat','lng')->get();
        $contacts = Contact::select('email','phone')->get();
        $devices = Device::with('prices.service')->get();
        if(count($branches) > 0) {
            foreach ($branches as $key => $value) {
                if($key == 0) {
                    Mapper::map($value->lat,
                        $value->lng,
                        [
                            'zoom' => 15,
                            'markers' =>
                                [
                                    'title' => $value->title,
                                    'content' => $value->address,
                                    'animation' => 'DROP'
                                ]
                        ]);
                } else {
                    Mapper::informationWindow($value->lat,
                        $value->lng,
                        $value->address,
                        [
                            'open' => false,
                            'title' => $value->title
                        ]);
                }
            }
        }
        $offers = Offer::select('description','image')
            ->where('active', 1)
            ->get();
        $array = [
            'offers' => $offers,
            'contacts' => $contacts,
            'devices' => $devices
        ];
        return view('home',$array);
    }
}
