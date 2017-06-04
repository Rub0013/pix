<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\CustomMail;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Support\Facades\Validator;
use App\User;

class MailController extends Controller
{
    public function userSendMail(Request $request, Mailer $mailer){
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'text' => 'required',
            'Viber' => 'nullable|numeric',
            'WhatsApp' => 'nullable|numeric',
        ]);
        if ($validator->fails())
        {
            return response()->json(array(
                'error' => $validator->getMessageBag()->toArray(),
                'success' => false
            ));
        } else {
            $name = $request['name'];
            $text = $request['text'];
            $email = $request['email'];
            $viber = $request['Viber'];
            $whatsApp = $request['WhatsApp'];
            $file = $request['file'];
            $adminEmail = User::select('email')->where('name','=','Admin')->first();
            $mailer->to($adminEmail->email)->send(new CustomMail($name,$email,$text,$viber,$whatsApp,$file));
            if ($mailer->failures()) {
                return response()->json(array(
                    'error' => true,
                    'success' => false,
                ));
            } else {
                return response()->json(array(
                    'error' => false,
                    'success' => true,
                ));
            }
        }
    }
}
