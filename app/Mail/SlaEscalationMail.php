<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

// This Mailable class is responsible for sending an email notification when a contribution has breached the Service Level Agreement (SLA) for review. 
// It takes a contribution object, the number of days it has been pending, and the coordinator responsible for the review as parameters in the constructor. 
// The build method constructs the email by setting a subject and specifying the view that should be used to render the email content. 
// The view will have access to the contribution, daysPending, and coordinator variables to provide relevant information about the SLA breach and the responsible coordinator.
class SlaEscalationMail extends Mailable
{
    use SerializesModels;

    public $contribution;
    public $daysPending;
    public $coordinator;

    // Create a new message instance.
    public function __construct($contribution, $daysPending, $coordinator)
    {
        $this->contribution = $contribution;
        $this->daysPending = $daysPending;
        $this->coordinator = $coordinator;
    }

    // Build the message.
    public function build()
    {
        return $this->subject('SLA Breach Escalation – Contribution Review')
            ->view('emails.sla_escalation'); // This view will display the contribution details, days pending, and coordinator information to the recipient.
    }
}