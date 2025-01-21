<?php

namespace App\Services\CartService;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Services\BaseService\BaseService;
use App\Services\CartService\CartServiceInterface;
use App\Models\Cart;

class CartService extends BaseService implements CartServiceInterface {

    public function __construct(Cart $model) {
        parent::__construct($model);
    }

}
