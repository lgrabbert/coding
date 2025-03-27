<?php

declare(strict_types=1);

namespace App\Http\Response;

use App\Dto\OrderDto;

readonly class OrderDataResponse
{
    private function __construct(
        private OrderDto $order
    ) {}

    public static function fromApiData(array $data): self
    {
        return new self(
            order: OrderDto::fromArray($data)
        );
    }

    public function toArray(): array
    {
        return $this->order->toArray();
    }
}
