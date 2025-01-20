<?php

namespace App\Observers;

use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Str;
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

    public function saving(Product $model): void {
        // $model->forceFill([]);
        if (empty($model->sku)) {
            // Retrieve the maximum ID from the database
            $lastProductId = Product::max('id') ?? 0;

            $generatedSKU = $this->generateSKU($lastProductId);

            // Set the generated sku to the model
            $model->sku = $generatedSKU;
        }
    }

    private function generateSKU(int $lastProductId): string {
        $newProductId = $lastProductId + 1;
        return 'SKU_' . str_pad($newProductId, 5, '0', STR_PAD_LEFT);
    }
}
