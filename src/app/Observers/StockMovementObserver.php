<?php

namespace App\Observers;

// use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\StockMovement;
use App\Enums\StockMovementType;

class StockMovementObserver {

    public function created(StockMovement $model): void {
        $this->processStockAdjustment($model, $model->type, $model->quantity);
    }

    public function updated(StockMovement $model): void {
        $previousType = $model->getOriginal('type');
        $previousQuantity = $model->getOriginal('quantity');

        // Revert stock changes based on previous values
        $this->processStockReversal($model, $previousType, $previousQuantity);

        // Apply stock changes based on new values
        $this->processStockAdjustment($model, $model->type, $model->quantity);
    }

    private function processStockReversal(StockMovement $stockMovement, string $type, int $quantity): void {
        // $reversedQuantity = -$quantity;
        $reversedQuantity = $quantity * -1;
        $this->adjustStock($stockMovement, $type, $reversedQuantity);
    }

    private function processStockAdjustment(StockMovement $stockMovement, string $type, int $quantity): void {
        $this->adjustStock($stockMovement, $type, $quantity);
    }

    private function adjustStock(StockMovement $stockMovement, string $type, int $quantity): void {
        if (!StockMovementType::isValid($type)) {
            throw new Exception('Invalid StockMovementType: ' . $type);
        }

        $stock = $stockMovement->stock();

        match ($type) {
            StockMovementType::IN => $stock->increment('quantity', $quantity),
            StockMovementType::OUT => $stock->decrement('quantity', $quantity),
            default => throw new Exception('Unsupported StockMovementType: ' . $type),
        };
    }

}
