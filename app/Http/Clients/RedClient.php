<?php

declare(strict_types=1);

namespace App\Http\Clients;

use App\Enums\OrderType;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class RedClient
{
    public function createOrder(OrderType $type): Response
    {
        if (env('RED_PROVIDER_USE_MOCK', false)) {
            return Http::fake([
                '*' => Http::response([
                    'status' => 'completed',
                    'type' => $type->value,
                    'id' => '0.415w7r427cs'
                ], 201)
            ])->post('/order', []);
        }

            return Http::redProvider()
            ->post('/orders', [
                'type' => $type->value,
            ]);
    }

    public function deleteOrderById($redProviderPortalId): Response
    {
        if (env('RED_PROVIDER_USE_MOCK', false)) {
            return Http::fake([
                '*' => Http::response([
                ], 200)
            ])->delete('/order', []);
        }

        return Http::redProvider()
            ->delete('/order/' . $redProviderPortalId);
    }
}
