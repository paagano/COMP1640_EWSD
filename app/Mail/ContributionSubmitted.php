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

// This Mailable class is responsible for sending an email notification to a user when they submit a new contribution to the magazine. 
// It takes a Contribution object as a parameter in the constructor, which is then used to populate the email content. 
// The envelope method defines the subject of the email, while the content method specifies the view that should be used to render the email and passes the contribution data to the view. The attachments method is used to attach the submitted Word document to the email, allowing the recipient to easily access the
class ContributionSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public Contribution $contribution;

    // Create a new message instance.
    public function __construct(Contribution $contribution)
    {
        $this->contribution = $contribution;
    }

    // Get the message envelope.
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Magazine Contribution Submitted'
        );
    }

    // Get the message content definition.
    public function content(): Content
    {
        return new Content(
            view: 'emails.contribution_submitted',
            with: [
                'contribution' => $this->contribution,
            ],
        );
    }

    // Get the attachments for the message.
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