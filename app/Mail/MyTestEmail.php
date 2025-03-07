<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MyTestEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public User $user)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Account Has Been Activated',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $imagePath = public_path('images/spartav_logo.png');
        $imageData = file_get_contents($imagePath);
        
        $imagePath2 = public_path('images/check.png');
        $imageData2 = file_get_contents($imagePath2);

        return new Content(
            view: 'mails.test-email',
            with: [
                'imageData' => $imageData,
                'imageData2' => $imageData2,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
