<?php

namespace App\Services\NotificationService;

use App\Services\NotificationService\NotificationServiceInterface;

class ToastrNotification implements NotificationServiceInterface {
    //
    public function notify(array $data): void {
        session()->flash('toast', [
            ...$data,
            'type' => $data['type'] ?? 'info', // success, error, warning, info
            'message' => $data['message'] ?? '',
        ]);
    }
}
