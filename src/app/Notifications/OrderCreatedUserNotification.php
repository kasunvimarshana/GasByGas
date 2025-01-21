<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class OrderCreatedUserNotification extends Notification {
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
                    ->subject('Your Order Has Been Successfully Created')
                    ->greeting('Hello,')
                    ->line('We are excited to inform you that your order has been successfully created.')
                    ->action('View Order Details', route('orders.show', $this->order->id))
                    ->line('Thank you for choosing our service. We are here to help if you need assistance!');
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
                    ->title('Your Order Is Created!')
                    ->icon(asset('img/icon.png'))
                    ->body('We are thrilled to let you know that your order has been successfully created. Tap to view details!')
                    ->action('View Order', route('orders.show', $this->order->id))
                    ->badge(asset('img/icon.png'))
                    ->data(['url' => route('orders.show', $this->order->id)]);
    }
}
