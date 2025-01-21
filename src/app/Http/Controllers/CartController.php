<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
// use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController\BaseController;
use App\Services\NotificationService\NotificationServiceInterface;
use App\Services\PaginationService\PaginationServiceInterface;
use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Services\CartService\CartService;
use App\Models\Cart;
use App\Services\ProductService\ProductService;
use Exception;

class CartController extends BaseController {
    protected NotificationServiceInterface $notificationService;
    protected PaginationServiceInterface $paginationService;
    protected CartService $cartService;
    protected ProductService $productService;

    public function __construct(
        NotificationServiceInterface $notificationService,
        PaginationServiceInterface $paginationService,
        CartService $cartService,
        ProductService $productService
    ) {
        parent::__construct($notificationService);

        $this->notificationService = $notificationService;
        $this->paginationService = $paginationService;
        $this->cartService = $cartService;
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        // Get the authenticated user's company ID
        $companyId = optional(auth()->user()?->company)->id;

        try {
            $cartQueryBuilder = $this->cartService->query();

            $cartQueryBuilder = $this->filterByCompanyOrUser($cartQueryBuilder, $companyId);

            $carts = $this->paginationService->paginate($cartQueryBuilder,
                                                            $request->perPage ?? 15);

            // Handle JSON response if requested
            if ($request->expectsJson()) {
                return $this->formatJsonResponse(true, '', $carts, null);
            }

            return view('pages.carts.index', compact('carts'));
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

        $cartQueryBuilder = $this->cartService->query();
        $cartQueryBuilder = $this->filterByCompanyOrUser($cartQueryBuilder, $companyId);

        $productIds = $cartQueryBuilder->pluck('product_id');

        $productQueryBuilder = $this->productService->query();
        $productQueryBuilder = $productQueryBuilder->whereNotIn('id', $productIds);

        $products = $this->paginationService->paginate($productQueryBuilder,
                                                        $request->perPage ?? 15);

        return view('pages.carts.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCartRequest $request) {
        // Get the authenticated user's company ID
        $companyId = optional(auth()->user()?->company)->id;
        // Get the authenticated user's ID
        $userId = $userId ?? auth()->user()?->id;

        try {
            DB::beginTransaction(); // Main transaction

            $requestData = $request->all();

            if ($companyId) {
                $requestData = array_merge($requestData, [
                    'related_entity_id' => $companyId,
                    'related_entity_type' => \App\Models\Company::class,
                ]);
            } else {
                $requestData = array_merge($requestData, [
                    'related_entity_id' => $userId,
                    'related_entity_type' => \App\Models\User::class,
                ]);
            }

            $cart = $this->cartService->create($requestData);

            DB::commit(); // Commit transaction if all succeeds

            return $this->handleResponse(
                trans('messages.thank_you', []),
                null,
                'carts.index',
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
    public function show(Cart $cart) {
        return view('pages.carts.show', compact('cart'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart) {
        return view('pages.carts.edit', compact('cart'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCartRequest $request, Cart $cart) {
        // Get the authenticated user's company ID
        // $companyId = optional(auth()->user()?->company)->id;

        try {
            DB::beginTransaction(); // Main transaction

            $cart = $this->cartService->update($cart->id, $request->all());

            DB::commit(); // Commit transaction if all succeeds

            return $this->handleResponse(
                trans('messages.thank_you', []),
                null,
                'carts.index',
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
    public function destroy(Cart $cart) {
        // Get the authenticated user's company ID
        // $companyId = optional(auth()->user()?->company)->id;

        try {
            DB::beginTransaction(); // Main transaction

            $this->cartService->delete($cart->id);

            DB::commit(); // Commit transaction if all succeeds

            return $this->handleResponse(
                trans('messages.thank_you', []),
                null,
                'carts.index',
                [],
            );
        } catch (Exception $e) {
            DB::rollBack(); // Rollback if any operation fails

            return $this->handleException($e, trans('messages.general_error', []));
        }
    }

}
