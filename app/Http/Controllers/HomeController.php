<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cornford\Googlmapper\Facades\MapperFacade as Mapper;

class HomeController extends MainController
{
    public function show(){
        Mapper::map(40.219792 , 44.486872,  ['zoom' => 10, 'markers' => ['title' => 'My Location']]);
        return view('home');
    }
}
