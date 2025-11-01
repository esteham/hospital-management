<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\DoctorMessage;

class DoctorMentionMail extends Mailable
{
    use Queueable, SerializesModels;

    public DoctorMessage $message;

    /**
     * Create a new message instance.
     */
    public function __construct(DoctorMessage $message)
    {
        $this->message = $message;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('New Message from Admin - Schedule Update')
                    ->markdown('emails.doctor_mention', [
                        'message' => $this->message,
                        'doctor' => $this->message->doctor,
                        'admin' => $this->message->admin,
                        'schedule' => $this->message->schedule,
                    ]);
    }
}
