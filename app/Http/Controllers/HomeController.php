<?php

namespace App\Http\Controllers;

use App\Branch;
use Illuminate\Http\Request;
use Cornford\Googlmapper\Facades\MapperFacade as Mapper;

class HomeController extends MainController
{
    public function show(){
        $branches = Branch::select('title','address','lat','lng')->get();
        if(count($branches) > 0) {
            Mapper::map($branches[0]->lat, $branches[0]->lng,  ['zoom' => 15, 'markers' => ['title' => $branches[0]->title, 'animation' => 'DROP']])
                ->informationWindow($branches[0]->lat, $branches[0]->lng, $branches[0]->address, ['markers' => ['animation' => 'DROP']]);
//            if(count($branches) > 1){
//                Mapper::informationWindow(55.731059, 37.550435, 'Content', ['open' => false, 'maxWidth'=> 300, 'markers' => ['title' => 'Title']]);
//            }
        }
        return view('home');
    }
}
