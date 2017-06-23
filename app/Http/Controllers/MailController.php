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
            'mobileNumber' => 'nullable|numeric',
            'Viber' => 'nullable|numeric',
            'WhatsApp' => 'nullable|numeric',
            'callTime' => 'nullable|date',
        ]);
        if ($validator->fails())
        {
            return response()->json(array(
                'error' => true,
                'success' => false,
                'message' => $validator->errors()->first()
            ));
        } else {
            $name = $request['name'];
            $text = $request['text'];
            $email = $request['email'];
            $mobileNumber = $request['mobileNumber'];
            $viber = $request['Viber'];
            $whatsApp = $request['WhatsApp'];
            $callTime = $request['callTime'];
            $file = $request['file'];
            $adminEmail = User::select('email')->where('name','=','Admin')->first();
            $mailer->to($adminEmail->email)->send(new CustomMail($name, $email, $text,
                $mobileNumber, $viber, $whatsApp, $callTime, $file));
            if ($mailer->failures()) {
                return response()->json(array(
                    'error' => true,
                    'success' => false,
                    'message' => 'Проблемы с отправкой сообщения.'
                ));
            } else {
                return response()->json(array(
                    'error' => false,
                    'success' => true,
                    'message' => 'Сообщение успешно отправлено.'
                ));
            }
        }
    }
}
