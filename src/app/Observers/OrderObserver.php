<?php
namespace App\Observers;

// use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Order;

class OrderObserver {
    const TOLERANCE_PERIOD_IN_DAYS = 14;
    /**
     * Handle the Order "saving" event.
     *
     * @param \App\Models\Order $model
     * @return void
     */
    public function saving(Order $model): void {
        // $model->forceFill([]);
        if (empty($model->token)) {
            // Retrieve the maximum ID from the database
            $lastOrderId = Order::max('id') ?? 0;

            $generatedToken = $this->generateToken($lastOrderId);

            // Set the generated token to the model
            $model->token = $generatedToken;
        }

        // Handle the expected_pickup_date logic
        if (empty($model->expected_pickup_date)) {
            // Default pickup date: Current date + tolerance period in days (if set)
            $tolerancePeriod = $model->tolerance_period_in_days ?? self::TOLERANCE_PERIOD_IN_DAYS;
            $model->expected_pickup_date = now()->addDays($tolerancePeriod);
        }

        // Optionally handle tolerance_period_in_days if it’s not set
        if (empty($model->tolerance_period_in_days)) {
            $model->tolerance_period_in_days = self::TOLERANCE_PERIOD_IN_DAYS;
        }
    }

    private function generateToken(int $lastOrderId): string {
        $newOrderId = $lastOrderId + 1;
        return 'PO_' . str_pad($newOrderId, 5, '0', STR_PAD_LEFT);
    }

}
