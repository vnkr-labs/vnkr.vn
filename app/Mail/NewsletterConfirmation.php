<?php

namespace App\Mail;

use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly NewsletterSubscriber $subscriber
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Xác nhận đăng ký nhận tin VNKR',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.newsletter.confirm',
            with: [
                'confirmUrl'   => route('newsletter.confirm', $this->subscriber->token),
                'unsubUrl'     => route('newsletter.unsubscribe', $this->subscriber->token),
                'subscriberEmail' => $this->subscriber->email,
            ],
        );
    }
}
