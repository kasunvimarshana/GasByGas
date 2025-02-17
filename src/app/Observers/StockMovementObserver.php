<?php

namespace App\Observers;

// use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\StockMovement;
use App\Enums\StockMovementType;

class StockMovementObserver {

    public function created(StockMovement $model): void {
        $this->processStockAdjustment($model, $model->type?->value, $model->quantity);
    }

    public function updated(StockMovement $model): void {
        $previousType = $model->getOriginal('type');
        $previousQuantity = $model->getOriginal('quantity');

        // Revert stock changes based on previous values
        $this->processStockReversal($model, $previousType?->value, $previousQuantity);

        // Apply stock changes based on new values
        $this->processStockAdjustment($model, $model->type?->value, $model->quantity);
    }

    public function deleted(StockMovement $model): void {
        // Reverse stock adjustment when the movement is deleted
        $this->processStockReversal($model, $model->type?->value, $model->quantity);
    }

    public function restored(StockMovement $model): void {
        // Apply stock adjustment again when the movement is restored
        $this->processStockAdjustment($model, $model->type?->value, $model->quantity);
    }

    public function forceDeleted(StockMovement $model): void {
        // Reverse stock adjustment when the movement is force deleted
        $this->processStockReversal($model, $model->type?->value, $model->quantity);
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
            throw new Exception(trans('messages.invalid_stock_movement_type', [
                'type' => $type,
                'allowed_types' => implode(', ', array_column(StockMovementType::cases(), 'value')),
            ]));
        }


        $stock = $stockMovement->stock();

        match ($type) {
            StockMovementType::IN->value => $stock->increment('quantity', $quantity),
            StockMovementType::OUT->value => $stock->decrement('quantity', $quantity),
            default => throw new Exception(trans('messages.invalid_stock_movement_type', ['type' => $type, 'allowed_types' => implode(', ', array_column(StockMovementType::cases(), 'value'))])),
        };
    }

}
