<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\{Content, Envelope};
use Illuminate\Queue\SerializesModels;

class SendPasswordToNewUserMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public string $password,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: config('mail.from.address'),
            subject: 'Send Password To New User',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.send-password-to-new-user',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
