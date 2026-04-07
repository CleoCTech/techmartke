<?php

namespace App\Mail;

use App\Models\TradeInRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TradeInRequestNotification extends Mailable
{
    use Queueable, SerializesModels;

    public TradeInRequest $tradeIn;

    public function __construct(TradeInRequest $tradeIn)
    {
        $this->tradeIn = $tradeIn;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔁 New Trade-In Request — TechMart KE',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.trade-in-request',
            with: ['tradeIn' => $this->tradeIn],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
