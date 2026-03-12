<?php

namespace App\Mail;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ActionTakerAssigned extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $meeting;
    public $participant;

    public function __construct(Meeting $meeting, User $participant)
    {
        $this->meeting = $meeting;
        $this->participant = $participant;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Penugasan Tindak Lanjut Meeting: ' . $this->meeting->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.meetings.action_taker_assigned',
        );
    }
}
