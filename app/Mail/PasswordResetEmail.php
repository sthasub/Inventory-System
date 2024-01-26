<?php

namespace App\Mail;

use App\Models\PasswordResetToken;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        protected PasswordResetToken $passwordResetToken
    )
    {

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(  config('mail.from.address'), config('mail.from.name')),
            subject: 'Password Reset Email',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $url = "http://localhost:5173/reset-password?email={$this->passwordResetToken->email}&token={$this->passwordResetToken->token}";
        return new Content(
            view: 'email.reset-password',
            with: [
                'url'=>$url
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
