<?php

namespace App\Services\OrderItemService;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Services\BaseService\BaseService;
use App\Services\OrderItemService\OrderItemServiceInterface;
use App\Models\OrderItem;

class OrderItemService extends BaseService implements OrderItemServiceInterface {

    public function __construct(OrderItem $model) {
        parent::__construct($model);
    }

}
