<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Dto\RedClientDto;
use App\Enums\OrderType;
use App\Http\Request\SortOrdersRequest;
use App\Http\Request\StoreOrderRequest;
use App\Jobs\CheckOrderStatusJob;
use App\Services\OrderService;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Throwable;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(SortOrdersRequest $request)
    {
        return $this->orderService->getOrders($request->validated('name'), $request->validated('date'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $responseFromRedClient = $this->orderService->createRedClientOrder(OrderType::from($request->validated('type')));

        if ($responseFromRedClient->getStatusCode() !== 201) {
            return response()->json('Error create order failed for redClient', 422);
        }

        $redClientOrderDto = RedClientDto::fromArray($responseFromRedClient->json());

        try {
            $newOrder = $this->orderService->saveOrderToDatabase($redClientOrderDto, $request->validated('name'));
            //revert redClientOrder or using a job and retry later, lets talk about it
        } catch (Throwable $t) {

            return response()->json('Error save order failed to database', 422);
        }

        //check later if the status changed
        CheckOrderStatusJob::dispatch($newOrder)
            ->onQueue('order-checks')
            ->delay(now()->addMinutes(1));

        return response()->json($newOrder, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $redProviderPortalId)
    {
        $order = $this->orderService->findOrderById($redProviderPortalId);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json([
            'id' => $order->id,
            'type' => $order->type,
            'status' => $order->status
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $redProviderPortalId)
    {
        try {
            $this->orderService->deleteOrderByIdFromDatabase($redProviderPortalId);
        } catch (Throwable $t) {
            return response()->json('Error delete order failed from database', 422);
        }

        try {
            $this->orderService->deleteOrderByIdFromRedClient($redProviderPortalId);
        } catch (RequestException
        $e) {
            if ($e->response->getStatusCode() !== 400) {
                return response()->json('Error delete order failed from redClient', 422);
            }
        }

        return response()->json('Order deleted', 204);
    }
}
