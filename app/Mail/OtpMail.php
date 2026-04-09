<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $name;
    public $phone;

    public function __construct($otp, $name, $phone)
    {
        $this->otp = $otp;
        $this->name = $name;
        $this->phone = $phone;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre code OTP - Bkassoua',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.otp',
        );
    }
}