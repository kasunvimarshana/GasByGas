<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
// use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController\BaseController;
use App\Services\NotificationService\NotificationServiceInterface;
use App\Services\PaginationService\PaginationServiceInterface;
use App\Http\Requests\StoreStockMovementRequest;
use App\Http\Requests\UpdateStockMovementRequest;
use App\Services\StockMovementService\StockMovementService;
use App\Models\StockMovement;
use App\Services\StockService\StockService;
use Exception;

class StockMovementController extends BaseController {
    protected NotificationServiceInterface $notificationService;
    protected PaginationServiceInterface $paginationService;
    protected StockMovementService $stockMovementService;
    protected StockService $stockService;

    public function __construct(
        NotificationServiceInterface $notificationService,
        PaginationServiceInterface $paginationService,
        StockMovementService $stockMovementService,
        StockService $stockService,
    ) {
        parent::__construct($notificationService);

        $this->notificationService = $notificationService;
        $this->paginationService = $paginationService;
        $this->stockMovementService = $stockMovementService;
        $this->stockService = $stockService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        // Get the authenticated user's company ID
        $companyId = optional(auth()->user()?->company)->id;
        $stockId = $request->route('stock'); // $request->query('stock')
        $stockMovementId = $request->route('stockMovement'); // $request->query('stockMovement')

        try {
            $stockMovementQueryBuilder = $this->stockMovementService->query();
            // $stockMovementQueryBuilder = $stockMovementQueryBuilder->join('stocks', 'stock_movements.stock_id', '=', 'stocks.id');

            $stockMovementQueryBuilder = $stockMovementQueryBuilder->whereHas('stock', function($q) use ($stockId) {
                $q->where('stocks.id', $stockId);
            });

            $stockMovements = $this->paginationService->paginate($stockMovementQueryBuilder,
                                                            $request->perPage ?? 15);

            $stock = ($stockId) ? $this->stockService->getById($stockId) : null;

            $stockMovement = ($stockMovementId) ? $this->stockMovementService->getById($stockMovementId) : null;

            // Handle JSON response if requested
            if ($request->expectsJson()) {
                return $this->formatJsonResponse(true, '', $stockMovements, null);
            }

            return view('pages.stock-movements.index', compact('stockMovements', 'stockMovement', 'stock'));
        } catch (Exception $e) {
            return $this->handleException($e, trans('messages.general_error', []));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStockMovementRequest $request) {
        // Get the authenticated user's company ID
        $companyId = optional(auth()->user()?->company)->id;
        $stockId = $request->route('stock'); // $request->query('stock')

        try {
            DB::beginTransaction(); // Main transaction

            $requestData = array_merge($request->all(), [
                'stock_id' => $stockId,
            ]);

            $stockMovement = $this->stockMovementService->create($requestData);

            DB::commit(); // Commit transaction if all succeeds

            return $this->handleResponse(
                trans('messages.thank_you', []),
                null,
                'stock-movements.index',
                ['stock' => $stockMovement->stock?->id],
            );
        } catch (Exception $e) {
            DB::rollBack(); // Rollback if any operation fails

            return $this->handleException($e, trans('messages.general_error', []));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(StockMovement $stockMovement) {
        return view('pages.stock-movements.show', compact('stockMovement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStockMovementRequest $request, StockMovement $stockMovement) {
        // Get the authenticated user's company ID
        // $companyId = optional(auth()->user()?->company)->id;

        try {
            DB::beginTransaction(); // Main transaction

            $stockMovement = $this->stockMovementService->update($stockMovement->id, $request->all());

            DB::commit(); // Commit transaction if all succeeds

            return $this->handleResponse(
                trans('messages.thank_you', []),
                null,
                'stock-movements.index',
                ['stock' => $stockMovement->stock?->id],
            );
        } catch (Exception $e) {
            DB::rollBack(); // Rollback if any operation fails

            return $this->handleException($e, trans('messages.general_error', []));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockMovement $stockMovement) {
        // Get the authenticated user's company ID
        // $companyId = optional(auth()->user()?->company)->id;

        try {
            DB::beginTransaction(); // Main transaction

            $this->stockMovementService->delete($stockMovement->id);

            DB::commit(); // Commit transaction if all succeeds

            return $this->handleResponse(
                trans('messages.thank_you', []),
                null,
                'stock-movements.index',
                ['stock' => $stockMovement->stock?->id],
            );
        } catch (Exception $e) {
            DB::rollBack(); // Rollback if any operation fails

            return $this->handleException($e, trans('messages.general_error', []));
        }
    }
}
