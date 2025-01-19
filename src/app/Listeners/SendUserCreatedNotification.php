<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Events\UserCreated;
use App\Notifications\UserCreatedNotification;
use App\Mail\UserCreatedMail;

class SendUserCreatedNotification {
    /**
     * Create the event listener.
     */
    public function __construct() {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserCreated $event): void {
        //
        // Mail::to($event->user->email)->send(new UserCreatedMail($event->user));
        $event->user->notify(new UserCreatedNotification($event->user));
    }
}
