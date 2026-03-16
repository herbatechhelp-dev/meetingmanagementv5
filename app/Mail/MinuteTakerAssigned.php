<?php

namespace App\Mail;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MinuteTakerAssigned extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $meeting;
    public $participant;
    public $senderName;
    public $senderEmail;

    public function __construct(Meeting $meeting, User $participant, $senderName = null, $senderEmail = null)
    {
        $this->meeting = $meeting;
        $this->participant = $participant;
        $this->senderName = $senderName;
        $this->senderEmail = $senderEmail;
    }

    public function envelope(): Envelope
    {
        $from = $this->senderEmail ? new Address($this->senderEmail, $this->senderName) : null;

        return new Envelope(
            from: $from,
            subject: 'Penugasan Notulensi: ' . $this->meeting->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.meetings.minute_taker_assigned',
        );
    }
}
