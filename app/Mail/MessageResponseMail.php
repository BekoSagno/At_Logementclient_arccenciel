<?php

namespace App\Mail;

use App\Models\Message;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MessageResponseMail extends Mailable
{
    use SerializesModels;

    public Message $message;
    public string $response;

    /**
     * Create a new message instance.
     */
    public function __construct(Message $message, string $response)
    {
        $this->message = $message;
        $this->response = $response;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Réponse à votre message - AT Logement',
            from: config('mail.from.address', 'noreply@arccenciel.com'),
            replyTo: config('mail.from.address', 'noreply@arccenciel.com'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.message-response',
            with: [
                'messageModel' => $this->message,
                'response' => $this->response,
            ],
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
