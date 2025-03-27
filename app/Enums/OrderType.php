<?php

declare(strict_types=1);

namespace App\Enums;
enum OrderType: string
{
    case CONNECTOR = 'connector';
    case VPN_CONNECTION = 'vpn_connection';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

