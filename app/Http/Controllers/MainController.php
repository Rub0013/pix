<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

class MainController extends Controller
{
    public function imageUpload(Request $request){
        $rules = [
            'image' => 'image',
        ];
        $this->validate($request, $rules);
        $imageName = $request['name'];
        Storage::disk('upload')->put($imageName,File::get($request['image']));
        $exists = Storage::disk('upload')->exists($imageName);
        return response()->json([
            'error' => !$exists,
            'success' => $exists,
        ]);
    }
}
