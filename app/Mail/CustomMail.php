<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomMail extends Mailable
{
    use Queueable, SerializesModels;

    private $userEmail;
    public $userName;
    public $bodyMessage;
    public $phoneNumber;
    public $viber;
    public $whatsapp;
    public $callTime;
    private $file;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $email, $text, $phoneNumber = null, $viber = null,
                                $whatsapp = null, $callTime = null, $file) {
        $this->userName = $name;
        $this->userEmail = $email;
        $this->bodyMessage = $text;
        if($phoneNumber) {
            $this->phoneNumber = $phoneNumber;
        }
        if($viber) {
            $this->viber = $viber;
        }
        if($whatsapp) {
            $this->whatsapp = $whatsapp;
        }
        if($phoneNumber || $viber || $whatsapp) {
            if($callTime) {
                $this->callTime = $callTime;
            } else {
                $this->callTime = 'Не указано';
            }
        }
        if($file) {
            $this->file = $file;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->file) {
            $mailBody = $this->from($this->userEmail)
                ->view('emails.custom')
                ->attach($this->file->getRealPath(), array(
                        'as' => 'file.' . $this->file->getClientOriginalExtension(),
                        'mime' => $this->file->getMimeType())
                );
        } else {
            $mailBody = $this->from($this->userEmail)
                ->view('emails.custom');
        }
        return $mailBody;
    }
}
