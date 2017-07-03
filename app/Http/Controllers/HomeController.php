<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cornford\Googlmapper\Facades\MapperFacade as Mapper;

class HomeController extends MainController
{
    public function show(){
        Mapper::map(55.731492, 37.549373,  ['zoom' => 15, 'markers' => ['title' => 'My Location1', 'animation' => 'DROP']]);
        Mapper::informationWindow(55.731059, 37.550435, 'Content', ['open' => false, 'maxWidth'=> 300, 'markers' => ['title' => 'Title']]);
        return view('home');
    }
}
