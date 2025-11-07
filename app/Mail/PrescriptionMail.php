<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Appointment;
use App\Models\Prescription;

class PrescriptionMail extends Mailable
{
    use Queueable, SerializesModels;

    public Appointment $appointment;
    public Prescription $prescription;
    public $pdf;

    /**
     * Create a new message instance.
     */
    public function __construct(Appointment $appointment, Prescription $prescription, $pdf)
    {
        $this->appointment = $appointment;
        $this->prescription = $prescription;
        $this->pdf = $pdf;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Prescription - ' . $this->appointment->booking_id,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.prescription',
            with: [
                'appointment' => $this->appointment,
                'prescription' => $this->prescription,
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
        return [
            \Illuminate\Mail\Mailables\Attachment::fromData(fn () => $this->pdf->output(), 'prescription_' . $this->appointment->booking_id . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
