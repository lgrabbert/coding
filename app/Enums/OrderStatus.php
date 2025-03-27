<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderStatus: string
{
    case ORDERED = 'ordered';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';

    public function canBeDeleted(): bool
    {
        return $this === self::COMPLETED;
    }
}

