<?php

namespace App\Observers;

// use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\StockMovement;
use App\Enums\StockMovementType;

class StockMovementObserver {

    public function saving(StockMovement $model): void {
        // Ensure the StockMovementType is valid
        if (!StockMovementType::isValid($model->type)) {
            throw new Exception('Invalid StockMovementType: ' . $model->type);
        }

        if ($model->type === StockMovementType::IN) {
            // Increment the stock quantity
            $model->stock()->increment('quantity', $model->quantity);
        } elseif ($model->type === StockMovementType::OUT) {
            // Decrement the stock quantity
            $model->stock()->decrement('quantity', $model->quantity);
        }
    }

}
