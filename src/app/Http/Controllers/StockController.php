<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
// use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController\BaseController;
use App\Services\NotificationService\NotificationServiceInterface;
use App\Services\PaginationService\PaginationServiceInterface;
use App\Http\Requests\StoreStockRequest;
use App\Http\Requests\UpdateStockRequest;
use App\Services\StockService\StockService;
use App\Models\Stock;
use App\Services\ProductService\ProductService;
use Exception;

class StockController extends BaseController {
    protected NotificationServiceInterface $notificationService;
    protected PaginationServiceInterface $paginationService;
    protected StockService $stockService;
    protected ProductService $productService;

    public function __construct(
        NotificationServiceInterface $notificationService,
        PaginationServiceInterface $paginationService,
        StockService $stockService,
        ProductService $productService
    ) {
        parent::__construct($notificationService);

        $this->notificationService = $notificationService;
        $this->paginationService = $paginationService;
        $this->stockService = $stockService;
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        // Get the authenticated user's company ID
        $companyId = optional(auth()->user()?->company)->id;

        try {
            $stockQueryBuilder = $this->stockService->query();

            $stockQueryBuilder = $stockQueryBuilder->where(function($q) use ($companyId) {
                $q->where('company_id', $companyId);
            });

            $stocks = $this->paginationService->paginate($stockQueryBuilder,
                                                            $request->perPage ?? 15);

            // Handle JSON response if requested
            if ($request->expectsJson()) {
                return $this->formatJsonResponse(true, '', $stocks, null);
            }

            return view('pages.stocks.index', compact('stocks'));
        } catch (Exception $e) {
            return $this->handleException($e, trans('messages.general_error', []));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        // Get the authenticated user's company ID
        $companyId = optional(auth()->user()?->company)->id;

        $productIds = $this->stockService->query()
            ->whereHas('company', fn($query) => $query->where('companies.id', $companyId))
            ->pluck('product_id');

        $products = $this->productService->query()
            ->whereNotIn('id', $productIds)
            ->get();

        return view('pages.stocks.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStockRequest $request) {
        // Get the authenticated user's company ID
        $companyId = optional(auth()->user()?->company)->id;

        try {
            DB::beginTransaction(); // Main transaction

            $requestData = array_merge($request->all(), [
                'company_id' => $companyId,
            ]);

            $stock = $this->stockService->create($requestData);

            DB::commit(); // Commit transaction if all succeeds

            return $this->handleResponse(
                trans('messages.thank_you', []),
                null,
                'stocks.index',
                [],
            );
        } catch (Exception $e) {
            DB::rollBack(); // Rollback if any operation fails

            return $this->handleException($e, trans('messages.general_error', []));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Stock $stock) {
        return view('pages.stocks.show', compact('stock'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stock $stock) {
        // Get the authenticated user's company ID
        $companyId = optional(auth()->user()?->company)->id;

        $productIds = $this->stockService->query()
            ->whereHas('company', fn($query) => $query->where('companies.id', $companyId))
            ->where('id', '!=', $stock->id)
            ->pluck('product_id');

        $products = $this->productService->query()
            ->where(function ($query) use ($productIds, $stock) {
                $query->whereNotIn('id', $productIds)->orWhere('id', $stock->product_id);
            })
            ->get();

        return view('pages.stocks.edit', compact('stock', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStockRequest $request, Stock $stock) {
        // Get the authenticated user's company ID
        // $companyId = optional(auth()->user()?->company)->id;

        try {
            DB::beginTransaction(); // Main transaction

            $stock = $this->stockService->update($stock->id, $request->all());

            DB::commit(); // Commit transaction if all succeeds

            return $this->handleResponse(
                trans('messages.thank_you', []),
                null,
                'stocks.index',
                [],
            );
        } catch (Exception $e) {
            DB::rollBack(); // Rollback if any operation fails

            return $this->handleException($e, trans('messages.general_error', []));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stock $stock) {
        // Get the authenticated user's company ID
        // $companyId = optional(auth()->user()?->company)->id;

        try {
            DB::beginTransaction(); // Main transaction

            $this->stockService->delete($stock->id);

            DB::commit(); // Commit transaction if all succeeds

            return $this->handleResponse(
                trans('messages.thank_you', []),
                null,
                'stocks.index',
                [],
            );
        } catch (Exception $e) {
            DB::rollBack(); // Rollback if any operation fails

            return $this->handleException($e, trans('messages.general_error', []));
        }
    }
}
