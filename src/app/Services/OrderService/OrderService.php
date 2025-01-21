<?php

namespace App\Services\OrderService;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Services\BaseService\BaseService;
use App\Services\OrderService\OrderServiceInterface;
use App\Models\Order;

class OrderService extends BaseService implements OrderServiceInterface {

    public function __construct(Order $model) {
        parent::__construct($model);
    }

}
