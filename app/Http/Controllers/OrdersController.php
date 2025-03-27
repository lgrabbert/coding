<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Response\OrdersResponse;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

class OrdersController
{
    public function __construct(protected OrderService $orderService) {}

    /**
     * Display a listing of all orders.
     */
    public function index(): JsonResponse
    {
        $orders = json_decode(
            $this->orderService->getOrderList()->getBody()->getContents(),
            true
        );

        return response()->json(
            OrdersResponse::fromApiData($orders)->toArray(),
        );
    }
}
