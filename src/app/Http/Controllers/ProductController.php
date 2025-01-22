<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
// use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController\BaseController;
use App\Services\NotificationService\NotificationServiceInterface;
use App\Services\PaginationService\PaginationServiceInterface;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Services\ProductService\ProductService;
use App\Models\Product;
use Exception;

class ProductController extends BaseController {
    protected NotificationServiceInterface $notificationService;
    protected PaginationServiceInterface $paginationService;
    protected ProductService $productService;

    public function __construct(
        NotificationServiceInterface $notificationService,
        PaginationServiceInterface $paginationService,
        ProductService $productService
    ) {
        parent::__construct($notificationService);

        $this->notificationService = $notificationService;
        $this->paginationService = $paginationService;
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        try {
            $productQueryBuilder = $this->productService->query();

            $products = $this->paginationService->paginate($productQueryBuilder,
                                                            $request->perPage ?? 15);

            // Handle JSON response if requested
            if ($request->expectsJson()) {
                return $this->formatJsonResponse(true, '', $products, null);
            }

            return view('pages.products.index', compact('products'));
        } catch (Exception $e) {
            $friendlyMessage = $e->getMessage() ?? trans('messages.general_error', []);
            return $this->handleException($e, $friendlyMessage);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        return view('pages.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request) {
        try {
            DB::beginTransaction(); // Main transaction

            $product = $this->productService->create($request->all());

            DB::commit(); // Commit transaction if all succeeds

            return $this->handleResponse(
                trans('messages.thank_you', []),
                null,
                'products.index',
                [],
            );
        } catch (Exception $e) {
            DB::rollBack(); // Rollback if any operation fails

            $friendlyMessage = $e->getMessage() ?? trans('messages.general_error', []);
            return $this->handleException($e, $friendlyMessage);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product) {
        return view('pages.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product) {
        return view('pages.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product) {
        try {
            DB::beginTransaction(); // Main transaction

            $product = $this->productService->update($product->id, $request->all());

            DB::commit(); // Commit transaction if all succeeds

            return $this->handleResponse(
                trans('messages.thank_you', []),
                null,
                'products.index',
                [],
            );
        } catch (Exception $e) {
            DB::rollBack(); // Rollback if any operation fails

            $friendlyMessage = $e->getMessage() ?? trans('messages.general_error', []);
            return $this->handleException($e, $friendlyMessage);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product) {
        try {
            DB::beginTransaction(); // Main transaction

            $this->productService->delete($product->id);

            DB::commit(); // Commit transaction if all succeeds

            return $this->handleResponse(
                trans('messages.thank_you', []),
                null,
                'products.index',
                [],
            );
        } catch (Exception $e) {
            DB::rollBack(); // Rollback if any operation fails

            $friendlyMessage = $e->getMessage() ?? trans('messages.general_error', []);
            return $this->handleException($e, $friendlyMessage);
        }
    }
}
