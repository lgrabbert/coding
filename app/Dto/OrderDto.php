<?php

declare(strict_types=1);

namespace App\Dto;

readonly class OrderDto
{
    public function __construct(
        public string $id,
        public string $type,
        public string $status,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            type: $data['type'],
            status: $data['status']
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'status' => $this->status
        ];
    }
}
