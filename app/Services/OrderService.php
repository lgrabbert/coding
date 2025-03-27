<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\RedClientDto;
use App\Enums\OrderType;
use App\Http\Clients\RedClient;
use App\Models\Order;
use App\Repositories\OrderRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Client\Response;

class OrderService
{

    public function __construct(
        public OrderRepository $orderRepository,
        public RedClient       $redClient,
    )
    {
    }

    public function getOrders(?string $sortNameDirection, ?string $sortDateDirection): Collection
    {
        return $this->orderRepository->getAllOrders($sortNameDirection, $sortDateDirection);
    }

    public function createRedClientOrder(OrderType $type): Response
    {
        //first call client to save data
        return $this->redClient->createOrder($type);
    }

    public function saveOrderToDatabase(RedClientDto $order, string $name): Order
    {
        return $this->orderRepository->create($order, $name);
    }

    public function findOrderById(string $redProviderPortalId): ?Order

    {
        return $this->orderRepository->findOrderById($redProviderPortalId);
    }

    public function deleteOrderByIdFromDatabase(string $redProviderPortalId): int
    {
        return $this->orderRepository->deleteOrderById($redProviderPortalId);
    }

    public function deleteOrderByIdFromRedClient(string $redProviderPortalId): void
    {
        $this->redClient->deleteOrderById($redProviderPortalId);
    }
}
