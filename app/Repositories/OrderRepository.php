<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderRepository
{
    public function findById(string $id): ?Order
    {
        return Order::find($id);
    }

    public function getFiltered(?string $name = null, string $sortBy = 'created_at', string $direction = 'desc'): LengthAwarePaginator
    {
        $query = Order::query();

        if ($name) {
            $query->where('name', 'LIKE', "%{$name}%");
        }

        $allowedSortColumns = ['name', 'created_at'];
        $sortBy = in_array($sortBy, $allowedSortColumns) ? $sortBy : 'created_at';
        $direction = in_array($direction, ['asc', 'desc']) ? $direction : 'desc';

        return $query->orderBy($sortBy, $direction)->paginate(15);
    }

    public function create(string $name, OrderType $type): Order
    {
        return Order::create([
            'name' => $name,
            'type' => $type,
            'status' => OrderStatus::ORDERED
        ]);
    }

    public function updateStatus(Order $order, OrderStatus $status): bool
    {
        return $order->update(['status' => $status]);
    }

    public function delete(Order $order): bool
    {
        if (!$order->status->canBeDeleted()) {
            return false;
        }

        return $order->delete();
    }

    public function getByStatus(OrderStatus $status): Collection
    {
        return Order::where('status', $status)->get();
    }
}
