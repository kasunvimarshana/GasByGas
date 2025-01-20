<?php

namespace App\Observers;

use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\Company;
use App\Models\Product;

class ProductObserver {

    public function created(Product $product) {
        try {
            foreach (Company::all() as $key => $value) {
                $value->stocks()->updateOrCreate([
                    'product_id' => $product->id,
                ], [
                    'quantity' => 0,
                ]);
            }
        } catch (Exception $e) {
            Log::error('Error creating product: ' . $e->getMessage());
        }
    }
}
