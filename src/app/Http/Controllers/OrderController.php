<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
// use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController\BaseController;
use App\Services\NotificationService\NotificationServiceInterface;
use App\Services\PaginationService\PaginationServiceInterface;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Services\OrderService\OrderService;
use App\Services\OrderItemService\OrderItemService;
use App\Services\CartService\CartService;
use App\Services\StockService\StockService;
use App\Services\StockMovementService\StockMovementService;
use App\Models\Order;
use App\Enums\OrderStatus;
use App\Enums\StockMovementType;
use Exception;
use App\Events\OrderCreated;

class OrderController extends BaseController {
    protected NotificationServiceInterface $notificationService;
    protected PaginationServiceInterface $paginationService;
    protected OrderService $orderService;
    protected OrderItemService $orderItemService;
    protected CartService $cartService;
    protected StockService $stockService;
    protected StockMovementService $stockMovementService;

    public function __construct(
        NotificationServiceInterface $notificationService,
        PaginationServiceInterface $paginationService,
        OrderService $orderService,
        OrderItemService $orderItemService,
        CartService $cartService,
        StockService $stockService,
        StockMovementService $stockMovementService,
    ) {
        parent::__construct($notificationService);

        $this->notificationService = $notificationService;
        $this->paginationService = $paginationService;
        $this->orderService = $orderService;
        $this->orderItemService = $orderItemService;
        $this->cartService = $cartService;
        $this->stockService = $stockService;
        $this->stockMovementService = $stockMovementService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        // Get the authenticated user's company ID
        $companyId = optional(auth()->user()?->company)->id;

        try {
            $orderQueryBuilder = $this->orderService->query();

            $orderQueryBuilder = $this->filterByCompanyOrUser($orderQueryBuilder, $companyId);

            $orders = $this->paginationService->paginate($orderQueryBuilder,
                                                            $request->perPage ?? 15);

            // Handle JSON response if requested
            if ($request->expectsJson()) {
                return $this->formatJsonResponse(true, '', $orders, null);
            }

            return view('pages.orders.index', compact('orders'));
        } catch (Exception $e) {
            $friendlyMessage = $e->getMessage() ?? trans('messages.general_error', []);
            return $this->handleException($e, $friendlyMessage);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function indexForCompany(Request $request) {
        // Get the authenticated user's company ID
        $companyId = optional(auth()->user()?->company)->id;

        try {
            $orderQueryBuilder = $this->orderService->query();

            $orderQueryBuilder = $orderQueryBuilder->where(function($q) use ($companyId) {
                $q->where('company_id', $companyId);
            });

            $orders = $this->paginationService->paginate($orderQueryBuilder,
                                                            $request->perPage ?? 15);

            // Handle JSON response if requested
            if ($request->expectsJson()) {
                return $this->formatJsonResponse(true, '', $orders, null);
            }

            return view('pages.orders.index-for-company', compact('orders'));
        } catch (Exception $e) {
            $friendlyMessage = $e->getMessage() ?? trans('messages.general_error', []);
            return $this->handleException($e, $friendlyMessage);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request) {
        // Get the authenticated user's company ID
        $companyId = optional(auth()->user()?->company)->id;
        // Get the authenticated user's ID
        $userId = $userId ?? auth()->user()?->id;

        try {
            DB::beginTransaction(); // Main transaction

            $requestData = $request->all();

            if (!isset($requestData['company_id']) || empty($requestData['company_id'])) {
                throw new Exception(trans('messages.company_required'));
            }

            $carts = $this->getFilteredCarts($companyId);

            if ($carts->isEmpty()) {
                throw new Exception(trans('messages.cart_empty'));
            }

            $requestData = $this->prepareRelatedEntityData($requestData, $companyId, $userId);
            $requestData['status'] = OrderStatus::PENDING->value;

            $order = $this->orderService->create($requestData);

            $totalAmount = $this->processCartItemsWithStockCheck($carts, $order);

            $order = $this->orderService->update($order->id, ['amount' => $totalAmount]);

            DB::commit(); // Commit transaction if all succeeds

            // Fire the event
            event(new OrderCreated($order));

            return $this->handleResponse(
                trans('messages.thank_you', []),
                null,
                'orders.index',
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
    public function show(Order $order) {
        return view('pages.orders.show', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order) {
        // Get the authenticated user's company ID
        // $companyId = optional(auth()->user()?->company)->id;

        try {
            DB::beginTransaction(); // Main transaction

            // Check if the order is in a modifiable state
            if ($order->status?->value !== OrderStatus::PENDING->value) {
                throw new Exception(trans('messages.order_not_updatable'));
            }

            if ($request->input('status') == OrderStatus::CANCELLED->value) {
                $orderItemQueryBuilder = $this->orderItemService->query();
                $orderItemQueryBuilder = $orderItemQueryBuilder->where(function($q) use ($order) {
                    $q->where('order_id', $order->id);
                });
                $orderItems = $orderItemQueryBuilder->get() ?? collect();

                $orderItems->each(function ($orderItem) use ($order) {
                    $stock = $this->findStock($order->company_id, $orderItem->product_id);
                    if ($stock) {
                        $this->handleStockMovement($stock->id, $orderItem->quantity, StockMovementType::IN->value, $order);
                    }
                });
            }

            $order = $this->orderService->update($order->id, $request->all());

            DB::commit(); // Commit transaction if all succeeds

            return $this->handleResponse(
                trans('messages.thank_you', []),
                null,
                'orders.index-for-company',
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
    public function destroy(Order $order) {
        // Get the authenticated user's company ID
        // $companyId = optional(auth()->user()?->company)->id;
        try {
            DB::beginTransaction(); // Main transaction

            // Check if the order can be deleted
            if (in_array($order->status?->value, [OrderStatus::COMPLETED->value, OrderStatus::CANCELLED->value], true)) {
                throw new Exception(trans('messages.order_not_deletable'));
            }

            $orderItemQueryBuilder = $this->orderItemService->query();
            $orderItemQueryBuilder = $orderItemQueryBuilder->where(function($q) use ($order) {
                $q->where('order_id', $order->id);
            });
            $orderItems = $orderItemQueryBuilder->get() ?? collect();

            $orderItems->each(function ($orderItem) use ($order) {
                $stock = $this->findStock($order->company_id, $orderItem->product_id);
                if ($stock) {
                    $this->handleStockMovement($stock->id, $orderItem->quantity, StockMovementType::IN->value, $order);
                }
            });

            // Delete the order
            $this->orderService->delete($order->id);

            DB::commit(); // Commit transaction if all succeeds

            return $this->handleResponse(
                trans('messages.thank_you', []),
                null,
                'orders.index-for-company',
                [],
            );
        } catch (Exception $e) {
            DB::rollBack(); // Rollback if any operation fails

            $friendlyMessage = $e->getMessage() ?? trans('messages.general_error', []);
            return $this->handleException($e, $friendlyMessage);
        }
    }

    private function getFilteredCarts(?int $companyId = null, ?int $userId = null) {
        $cartQueryBuilder = $this->cartService->query();
        $cartQueryBuilder = $this->filterByCompanyOrUser($cartQueryBuilder, $companyId, $userId);

        return $cartQueryBuilder->get();
    }

    private function prepareRelatedEntityData(array $requestData, $companyId, $userId) {
        $relatedEntity = $companyId
            ? ['related_entity_id' => $companyId, 'related_entity_type' => \App\Models\Company::class]
            : ['related_entity_id' => $userId, 'related_entity_type' => \App\Models\User::class];

        return array_merge($requestData, $relatedEntity);
    }

    private function processCartItemsWithStockCheck($carts, $order): float {
        $totalAmount = 0;
        $carts->each(function ($cart) use ($order, &$totalAmount) {
            $stock = $this->findStock($order->company_id, $cart->product_id);

            if (!$stock) {
                throw new Exception(trans('messages.stock_unavailable', [
                    'product' => optional($cart->product)->name,
                ]));
            }

            if ($stock->quantity < $cart->quantity) {
                throw new Exception(trans('messages.insufficient_stock', [
                    'product' => optional($cart->product)->name,
                    'required' => $cart->quantity,
                    'available' => $stock->quantity,
                ]));
            }

            $this->handleStockMovement($stock->id, $cart->quantity, StockMovementType::OUT->value, $order);

            $price = optional($stock->product)->price ?? 0;
            $totalAmount += ($price * $cart->quantity);

            $this->orderItemService->create([
                'order_id' => $order->id,
                'product_id' => $cart->product_id,
                'quantity' => $cart->quantity,
                'price' => optional($stock->product)->price ?? 0,
            ]);

            $this->cartService->delete($cart->id);
        });

        return $totalAmount;
    }

    private function findStock($companyId, $productId) {
        return $this->stockService->query()
            ->where('company_id', $companyId)
            ->where('product_id', $productId)
            ->first();
    }

    private function handleStockMovement($stockId, $quantity, $type, $order) {
        if (!StockMovementType::isValid($type)) {
            throw new Exception(trans('messages.invalid_stock_movement_type', [
                'type' => $type,
                'allowed_types' => implode(', ', array_column(StockMovementType::cases(), 'value')),
            ]));
        }

        // $orderClassName = substr(strrchr(get_class($order), '\\'), 1);
        // $orderClassName = basename(str_replace('\\', '/', get_class($order)));
        $orderClassName = class_basename($order);

        $this->stockMovementService->create([
            'stock_id' => $stockId,
            'quantity' => $quantity,
            'type' => $type,
            'reference' => "{$orderClassName}:{$order->id}",
        ]);
    }

}
