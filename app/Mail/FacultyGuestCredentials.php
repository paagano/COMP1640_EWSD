<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

// This Mailable class is responsible for sending an email containing the guest account credentials for a specific faculty. 
// It takes the faculty name, guest email, and guest password as parameters in the constructor, which are then used to populate the email content. 
// The build method constructs the email by setting a subject and specifying the view that should be used to render the email content. 
// The view will have access to the faculty, email, and password variables to display the relevant information to the recipient.
class FacultyGuestCredentials extends Mailable
{
    public $faculty;
    public $email;
    public $password;

    // Create a new message instance.
    public function __construct($faculty, $email, $password)
    {
        $this->faculty = $faculty;
        $this->email = $email;
        $this->password = $password;
    }

    // Build the message.
    public function build()
    {
        return $this->subject('Faculty Guest Account Credentials')
            ->view('emails.faculty-guest-credentials'); // This view will display the faculty name, guest email, and guest password to the recipient.
    }
}