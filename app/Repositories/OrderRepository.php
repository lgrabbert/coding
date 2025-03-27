<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dto\RedClientDto;
use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository
{
    public function findOrderById(string $redProviderPortalId): Order|null
    {
        return Order::where('redProviderPortalId', $redProviderPortalId)->first();
    }

    public function getAllOrders(?string $sortNameDirection, ?string $sortDateDirection): Collection
    {
        return Order::query()
            ->when($sortNameDirection, fn($query) => $query->orderBy('name', $sortNameDirection))
            ->when($sortDateDirection, fn($query) => $query->orderBy('created_at', $sortDateDirection))
            ->get();
    }

    public function create(RedClientDto $orderDto, string $name): Order
    {
        return Order::create([
            'name' => $name,
            'redProviderPortalId' => $orderDto->id,
            'type' => $orderDto->type,
            'status' => $orderDto->status
        ]);
    }

    public function deleteOrderById(string $redProviderPortalId): int
    {
        return Order::where('redProviderPortalId', $redProviderPortalId)
            ->where('status', '!=', 'completed')
            ->delete();
    }

    public function getByStatus(OrderStatus $status): Collection
    {
        return Order::where('status', $status)->get();
    }
}
