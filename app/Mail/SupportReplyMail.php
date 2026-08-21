<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\SupportConversation;
use App\Models\SupportMessage;

class SupportReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public SupportConversation $conversation;
    public SupportMessage $replyMessage;

    public function __construct(SupportConversation $conversation, SupportMessage $replyMessage)
    {
        $this->conversation = $conversation;
        $this->replyMessage = $replyMessage;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->replyMessage->subject ?: $this->conversation->subject ?: 'Re: Support Ticket',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.support_reply',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
