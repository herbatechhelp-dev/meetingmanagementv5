<?php

namespace App\Mail;

use App\Models\ActionItem;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ActionItemUpdated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $actionItem;
    public $participant;
    public $senderName;
    public $senderEmail;

    public function __construct(ActionItem $actionItem, User $participant, $senderName = null, $senderEmail = null)
    {
        $this->actionItem = $actionItem;
        $this->participant = $participant;
        $this->senderName = $senderName;
        $this->senderEmail = $senderEmail;
    }

    public function envelope(): Envelope
    {
        $from = $this->senderEmail ? new Address($this->senderEmail, $this->senderName) : null;

        return new Envelope(
            from: $from,
            subject: '[Pembaruan] ' . $this->actionItem->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.meetings.action_item_updated',
        );
    }
}
