<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class WelcomeEmail extends Mailable
{
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject(
            'Welcome to Hydroponic Environment Monitoring System'
        )
        ->view('emails.welcome');
    }
}