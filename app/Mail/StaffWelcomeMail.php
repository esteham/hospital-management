<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StaffWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $name;
    public string $email;
    public string $plainPassword;

    public function __construct(string $name, string $email, string $plainPassword)
    {
        $this->name = $name;
        $this->email = $email;
        $this->plainPassword = $plainPassword;
    }


    public function build()
    {
        return $this->subject('Your Xet Hospital Staff Account')->markdown('emails.staff_welcome', [

            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->plainPassword,
            'loginUrl' => url('/login'),
        ]);
    }
}
