<?php

namespace App\Mail;

use App\Models\Contribution;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContributionStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public Contribution $contribution;
    public string $commentText;

    public function __construct(Contribution $contribution, string $commentText)
    {
        $this->contribution = $contribution;
        $this->commentText = $commentText;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Update on Your UoG Magazine Contribution'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contribution_status_updated',
            with: [
                'contribution' => $this->contribution,
                'commentText' => $this->commentText,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}