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

    public function __construct(
        public string $code,
        public string $purpose = 'email_verification'
    ) {}

    public function envelope(): Envelope
    {
        $subject = match ($this->purpose) {
            'password_reset' => 'Your Password Reset OTP Code',
            default => 'Your Email Verification OTP Code',
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            htmlString: "
                <div style='font-family: Arial, sans-serif; padding: 20px; color: #333;'>
                    <h2>Verification Code</h2>
                    <p>Your verification code for <strong>" . e($this->purpose) . "</strong> is:</p>
                    <div style='font-size: 28px; font-weight: bold; letter-spacing: 5px; color: #4F46E5; margin: 20px 0;'>
                        " . e($this->code) . "
                    </div>
                    <p>This code will expire in 10 minutes. If you did not request this, please ignore this email.</p>
                </div>
            "
        );
    }
}
