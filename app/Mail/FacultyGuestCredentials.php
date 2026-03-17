<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class FacultyGuestCredentials extends Mailable
{
    public $faculty;
    public $email;
    public $password;

    public function __construct($faculty, $email, $password)
    {
        $this->faculty = $faculty;
        $this->email = $email;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('Faculty Guest Account Credentials')
            ->view('emails.faculty-guest-credentials');
    }
}