<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerificationLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $token,
        public string $purpose = 'email_verification'
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verification Link Action Required'
        );
    }

    public function content(): Content
    {
        $link = url("/api/v1/verification/verify-token?purpose={$this->purpose}&token={$this->token}");

        return new Content(
            htmlString: "
                <div style='font-family: Arial, sans-serif; padding: 20px; color: #333;'>
                    <h2>Verification Link</h2>
                    <p>Click the button below to verify your request for <strong>" . e($this->purpose) . "</strong>:</p>
                    <div style='margin: 25px 0;'>
                        <a href='" . e($link) . "' style='background-color: #4F46E5; color: #fff; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: bold;'>
                            Verify Now
                        </a>
                    </div>
                    <p>If the button doesn't work, copy and paste this link in your browser:</p>
                    <p style='word-break: break-all; color: #666;'>" . e($link) . "</p>
                </div>
            "
        );
    }
}
