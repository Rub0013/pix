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
            foreach ($branches as $key => $value) {
                if($key == 0) {
                    Mapper::map($branches[$key]->lat,
                        $branches[$key]->lng,
                        [
                            'zoom' => 15,
                            'markers' =>
                                [
                                    'title' => $branches[$key]->title,
                                    'content' => $branches[$key]->address,
                                    'animation' => 'DROP'
                                ]
                        ]);
                } else {
                    Mapper::informationWindow($branches[$key]->lat,
                        $branches[$key]->lng,
                        $branches[$key]->address,
                        [
                            'open' => false,
                            'title' => $branches[$key]->title
                        ]);
                }
            }
        }
        return view('home');
    }
}
