<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\OrderCreated;
use App\Notifications\OrderCreatedCompanyNotification;
use App\Notifications\OrderCreatedUserNotification;

class SendOrderCreatedNotification {
    /**
     * Create the event listener.
     */
    public function __construct() {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void {
        //
        $order = $event->order;
        $orderRelatedEntity = $order->relatedEntity;
        if ($orderRelatedEntity instanceof \App\Models\User) {
            $orderRelatedEntity->notify(new OrderCreatedUserNotification($order));
        } elseif ($orderRelatedEntity instanceof \App\Models\Company) {
            $orderRelatedEntity->users->each(fn($user) => $user->notify(new OrderCreatedUserNotification($order)));
        }

        $order->company->users->each(fn($user) => $user->notify(new OrderCreatedCompanyNotification($order)));
    }
}
