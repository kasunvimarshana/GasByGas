<?php

namespace App\Services\StockService;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Services\BaseService\BaseService;
use App\Services\StockService\StockServiceInterface;
use App\Models\Stock;

class StockService extends BaseService implements StockServiceInterface {

    public function __construct(Stock $model) {
        parent::__construct($model);
    }

    public function create(array $data): Stock {
        return DB::transaction(function () use ($data) {
            try {
                // Create the stock.
                $stock = Stock::create($data);
                return $stock;
            } catch (Exception $e) {
                // DB::rollBack();
                throw $e;
            }
        });
    }

    public function update($id, array $data): Stock {
        return DB::transaction(function () use ($data, $id) {
            try {
                // Find the stock.
                $stock = Stock::findOrFail($id);

                // Update the stock.
                $stock->update($data);
                return $stock;
            } catch (Exception $e) {
                // DB::rollBack();
                throw $e;
            }
        });
    }

    public function delete($id): bool {
        return DB::transaction(function () use ($id) {
            try {
                // Find the stock.
                $stock = Stock::findOrFail($id);

                // Delete the stock.
                return $stock->delete();
            } catch (Exception $e) {
                // DB::rollBack();
                throw $e;
            }
        });
    }

}
