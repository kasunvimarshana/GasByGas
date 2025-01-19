<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserCreatedNotification extends Notification {
    use Queueable;

    protected $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($user) {
        //
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage {
        // return (new MailMessage)
        //             ->line('The introduction to the notification.')
        //             ->action('Notification Action', url('/'))
        //             ->line('Thank you for using our application!');

        $subject = trans('Welcome, :name!', ['name' => $this->user->name]);
        return (new MailMessage)
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    // ->replyTo(config('mail.reply_to.address'), config('mail.reply_to.name'))
                    ->subject($subject)
                    ->markdown('emails.user_created', [
                        'user' => $this->user,
                    ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array {
        return [
            //
        ];
    }
}
