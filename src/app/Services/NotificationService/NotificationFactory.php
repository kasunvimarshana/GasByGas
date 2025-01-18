<?php

namespace App\Services\NotificationService;

use App\Services\NotificationService\NotificationServiceInterface;
use App\Services\NotificationService\ToastrNotification;
use App\Services\NotificationService\SweetAlertNotification;

class NotificationFactory {
    //
    public static function create(string $type): NotificationServiceInterface {
        return match ($type) {
            'toastr' => new ToastrNotification(),
            'sweetalert' => new SweetAlertNotification(),
            default => throw new \InvalidArgumentException(trans('messages.invalid_input', [])),
        };
    }
}
