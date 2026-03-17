<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

// This Mailable class is responsible for sending reminder emails to marketing coordinators about pending contributions that require their review. 
// It takes a contribution object, the number of days it has been pending, and the type of reminder (friendly, warning, breach) as parameters in the constructor. 
// The build method constructs the email by setting the subject based on the type of reminder and specifying the view that should be used to render the email content. 
// The view will have access to the contribution, days pending, and type variables to customize the email message accordingly.
class CoordinatorReminderMail extends Mailable
{
    use SerializesModels;

    public $contribution;
    public $daysPending;
    public $type;

    // Create a new message instance.
    public function __construct($contribution, $daysPending, $type)
    {
        $this->contribution = $contribution;
        $this->daysPending = $daysPending;
        $this->type = $type;
    }

    // Build the message.
    public function build()
    {
        $subject = match($this->type) {
            'friendly' => 'Reminder: Contribution awaiting review', 
            'warning' => 'SLA Warning: Review deadline approaching', 
            'breach' => 'SLA Breach Alert: Contribution overdue',
        };

        return $this->subject($subject)
            ->view('emails.coordinator_reminder'); // This view will use the contribution, daysPending, and type variables to customize the email content based on the reminder type.
    }
}