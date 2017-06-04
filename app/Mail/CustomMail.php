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
    public $viber;
    public $whatsapp;
    private $file;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name,$email,$text,$viber = null,$whatsapp = null,$file)
    {
        $this->userName = $name;
        $this->userEmail = $email;
        $this->bodyMessage = $text;
        if($viber) {
            $this->viber = $viber;
        }
        if($whatsapp) {
            $this->whatsapp = $whatsapp;
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
