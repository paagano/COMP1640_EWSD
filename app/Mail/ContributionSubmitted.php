<?php

namespace App\Mail;

use App\Models\Contribution;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Facades\Storage;

class ContributionSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public Contribution $contribution;

    public function __construct(Contribution $contribution)
    {
        $this->contribution = $contribution;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Magazine Contribution Submitted'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contribution_submitted',
            with: [
                'contribution' => $this->contribution,
            ],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromStorageDisk(
                'public',
                $this->contribution->word_document_path
            )->as('Submitted_Document.docx')
        ];
    }
}