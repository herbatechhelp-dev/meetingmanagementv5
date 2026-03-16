<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MeetingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $title;
    public $message;
    public $url;
    public $icon;
    public $iconColor;
    public $senderName;
    public $senderEmail;

    /**
     * Create a new notification instance.
     */
    public function __construct($title, $message, $url, $icon = 'fa-bell', $iconColor = 'text-primary', $senderName = null, $senderEmail = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->url = $url;
        $this->icon = $icon;
        $this->iconColor = $iconColor;
        $this->senderName = $senderName;
        $this->senderEmail = $senderEmail;
    }

    /**
     * Get the notification's delivery channels.
     * Use database for in-app bell. You can add 'mail' later if needed.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Use only database for in-app bell to avoid doubling with manual Mail:: calls.
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
                    ->subject($this->title)
                    ->line($this->message)
                    ->action('Lihat Detail', url($this->url));

        if ($this->senderEmail) {
            $mail->from($this->senderEmail, $this->senderName);
        }

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'url' => $this->url,
            'icon' => $this->icon,
            'iconColor' => $this->iconColor,
        ];
    }
}
