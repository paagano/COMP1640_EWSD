<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CoordinatorReminderMail extends Mailable
{
    use SerializesModels;

    public $contribution;
    public $daysPending;
    public $type;

    public function __construct($contribution, $daysPending, $type)
    {
        $this->contribution = $contribution;
        $this->daysPending = $daysPending;
        $this->type = $type;
    }

    public function build()
    {
        $subject = match($this->type) {
            'friendly' => 'Reminder: Contribution awaiting review',
            'warning' => 'SLA Warning: Review deadline approaching',
            'breach' => 'SLA Breach Alert: Contribution overdue',
        };

        return $this->subject($subject)
            ->view('emails.coordinator_reminder');
    }
}