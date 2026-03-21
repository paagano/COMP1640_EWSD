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

// This Mailable class is responsible for sending an email notification to the faculty coordinator when a student submits a new contribution. 
// It takes a Contribution object as a parameter in the constructor, which contains all the details of the submitted contribution. 
// The build method constructs the email by setting the subject, specifying the view that should be used to render the email content, and attaching the contribution document if it exists.
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
        // SAFETY CHECK - Only attach if file exists to prevent email sending failure
        if (
            $this->contribution->word_document_path &&
            Storage::disk('public')->exists($this->contribution->word_document_path)
        ) {
            return [
                Attachment::fromStorageDisk(
                    'public',
                    $this->contribution->word_document_path
                )->as('Contribution_' . $this->contribution->id . '.docx')
            ];
        }

        // ❗ If file missing → send email WITHOUT attachment
        return [];
    }
}