<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SlaEscalationMail extends Mailable
{
    use SerializesModels;

    public $contribution;
    public $daysPending;
    public $coordinator;

    public function __construct($contribution, $daysPending, $coordinator)
    {
        $this->contribution = $contribution;
        $this->daysPending = $daysPending;
        $this->coordinator = $coordinator;
    }

    public function build()
    {
        return $this->subject('SLA Breach Escalation – Contribution Review')
            ->view('emails.sla_escalation');
    }
}