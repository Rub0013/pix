<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function panel(){
        return view('auth.admin.admin-panel');
    }

    public function profile(){
        return view('auth.admin.admin-profile');
    }

    public function chat(){
        return view('auth.admin.admin-chat');
    }
}
