<?php

declare(strict_types=1);

namespace App\Http\Response;

use App\Dto\OrderDto;
use Illuminate\Support\Collection;

readonly class OrdersResponse
{
    private function __construct(
        private Collection $orders
    ) {}

    public static function fromApiData(array $data): self
    {
        $orders = collect($data)->map(fn (array $orderData) => OrderDto::fromArray($orderData));

        return new self(
            orders: $orders
        );
    }

    public function toArray(): array
    {
        return $this->orders->map(fn (OrderDto $order) => $order->toArray())->toArray();
    }

    public function toCollection(): Collection
    {
        return $this->orders;
    }
}
