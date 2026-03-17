<?php

namespace App\Mail;

use App\Models\Contribution;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// This Mailable class is responsible for sending an email notification to a user when the status of their contribution has been updated. 
// It takes a Contribution object and a comment text as parameters in the constructor, which are then used to populate the email content. 
// The envelope method defines the subject of the email, while the content method specifies the view that should be used to render the email and passes the contribution and comment text data to the view. 
// The attachments method can be used to specify any files that should be attached to the email, but in this case, it returns an empty array, indicating that no attachments are included.
class ContributionStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public Contribution $contribution;
    public string $commentText;

    // Create a new message instance.
    public function __construct(Contribution $contribution, string $commentText)
    {
        $this->contribution = $contribution;
        $this->commentText = $commentText;
    }

    // Get the message envelope.
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Update on Your UoG Magazine Contribution'
        );
    }

    // Get the message content definition.
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

    // Get the attachments for the message.
    public function attachments(): array
    {
        return [];
    }
}