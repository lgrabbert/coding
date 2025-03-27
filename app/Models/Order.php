<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasUuids;

    protected $fillable = [
        'redProviderPortalId',
        'type',
        'status',
        'name',
    ];

    protected $casts = [
        'type' => OrderType::class,
        'status' => OrderStatus::class,
        'created_at' => 'datetime:d.m.Y H:i:s',
        'updated_at' => 'datetime:d.m.Y H:i:s',
    ];
}
