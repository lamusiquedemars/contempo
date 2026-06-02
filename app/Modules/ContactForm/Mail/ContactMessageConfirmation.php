<?php

namespace App\Modules\ContactForm\Mail;

use App\Modules\ContactForm\Data\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessageConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactMessage $messageData) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation de réception de votre message',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.contact-message-confirmation',
            with: [
                'messageData' => $this->messageData,
            ],
        );
    }
}
