<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\PackageBooking;
use Barryvdh\DomPDF\Facade\Pdf;

class PackageBookingConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public PackageBooking $packageBooking;

    /**
     * Create a new message instance.
     */
    public function __construct(PackageBooking $packageBooking)
    {
        $this->packageBooking = $packageBooking;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Package Booking Confirmation - ' . $this->packageBooking->id,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.package_booking_confirmation',
            with: [
                'packageBooking' => $this->packageBooking,
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
        $pdf = Pdf::loadView('pdfs.package_booking_receipt', ['packageBooking' => $this->packageBooking]);

        return [
            \Illuminate\Mail\Mailables\Attachment::fromData(fn () => $pdf->output(), 'package_booking_receipt_' . $this->packageBooking->id . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
