<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CancellationNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $packageBooking;

    /**
     * Create a new message instance.
     */
    public function __construct($packageBooking)
    {
        $this->packageBooking = $packageBooking;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Package Booking Cancellation Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.cancellation_notification',
            with: [
                'user' => $this->packageBooking->user,
                'package' => $this->packageBooking->healthCheck,
                'cancellationReason' => $this->packageBooking->cancellation_reason,
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
