<?php

namespace App\Services\NotificationService;

use App\Services\NotificationService\NotificationServiceInterface;

class SweetAlertNotification implements NotificationServiceInterface {
    //
    public function notify(array $data): void {
        session()->flash('swal', [
            ...$data,
            'title' => $data['title'] ?? '',
            'text' => $data['text'] ?? '',
            'icon' => $data['icon'] ?? 'info', // success, error, warning, info
            'confirmButtonText' => $data['confirmButtonText'] ?? trans('messages.ok', []),
        ]);
    }
}
