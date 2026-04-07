<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MessageRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $sender,
        public string $firstMessage,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->sender->prenom . ' t\'a envoyé une demande de message sur Meetly',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.message_request',
        );
    }
}
