<?php

namespace App\Services\StockMovementService;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Services\BaseService\BaseService;
use App\Services\StockMovementService\StockMovementServiceInterface;
use App\Models\StockMovement;

class StockMovementService extends BaseService implements StockMovementServiceInterface {

    public function __construct(StockMovement $model) {
        parent::__construct($model);
    }

    public function create(array $data): StockMovement {
        return DB::transaction(function () use ($data) {
            try {
                // Create the stockMovement.
                $stockMovement = StockMovement::create($data);
                return $stockMovement;
            } catch (Exception $e) {
                // DB::rollBack();
                throw $e;
            }
        });
    }

    public function update($id, array $data): StockMovement {
        return DB::transaction(function () use ($data, $id) {
            try {
                // Find the stockMovement.
                $stockMovement = StockMovement::findOrFail($id);

                // Update the stockMovement.
                $stockMovement->update($data);
                return $stockMovement;
            } catch (Exception $e) {
                // DB::rollBack();
                throw $e;
            }
        });
    }

    public function delete($id): bool {
        return DB::transaction(function () use ($id) {
            try {
                // Find the stockMovement.
                $stockMovement = StockMovement::findOrFail($id);

                // Delete the stockMovement.
                return $stockMovement->delete();
            } catch (Exception $e) {
                // DB::rollBack();
                throw $e;
            }
        });
    }

}
