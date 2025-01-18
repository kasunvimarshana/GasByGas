<?php

namespace App\Services\NotificationService;

interface NotificationServiceInterface {
    //
    public function notify(array $data): void;
}
