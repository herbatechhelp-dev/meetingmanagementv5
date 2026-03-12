<?php

namespace App\Mail;

use App\Models\ActionItem;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ActionItemAssigned extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $actionItem;
    public $participant;
    public $meeting;

    public function __construct(ActionItem $actionItem, User $participant)
    {
        $this->actionItem = $actionItem;
        $this->participant = $participant;
        $this->meeting = $actionItem->meeting;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tugas Baru: ' . $this->actionItem->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.meetings.action_item_assigned',
        );
    }
}
