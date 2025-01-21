<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class OrderCreatedCompanyNotification extends Notification {
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct($order) {
        //
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array {
        return ['mail', WebPushChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage {
        // return (new MailMessage)
        //             ->line('The introduction to the notification.')
        //             ->action('Notification Action', url('/'))
        //             ->line('Thank you for using our application!');
        return (new MailMessage)
                    ->subject('New Order Notification')
                    ->greeting('Dear Team,')
                    ->line('A new order has been placed. Please review the details and take the necessary actions.')
                    ->action('View Order Details', route('orders.show', $this->order->id))
                    ->line('Thank you for your prompt attention to this matter.');
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

    public function toWebPush($notifiable, $notification) {
        return (new WebPushMessage)
                ->title('New Order Alert')
                ->icon(asset('img/icon.png'))
                ->body('A new order has been created. Tap to review the details and manage it effectively.')
                ->action('View Order', route('orders.show', $this->order->id))
                ->badge(asset('img/icon.png'))
                ->data(['url' => route('orders.show', $this->order->id)]);
    }
}
