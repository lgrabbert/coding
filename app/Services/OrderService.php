<?php

declare(strict_types=1);

namespace App\Services;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class OrderService
{
    public function getOrderById(string $id)
    {
        try {
            $response = Http::redProvider()->get('/api/v1/orders/' . $id);

            if (!$response->successful()) {
                throw new HttpException(400, 'Bad Request from RedProvider.');
            }

        } catch (Throwable $e) {
            Log::warning('RedProvider not reachable', [
                'error' => $e->getMessage(),
                'endpoint' => '/api/v1/orders/' . $id,
                'orderId' => $id
            ]);

            throw new RuntimeException('Failed to fetch order from RedProvider.');
        }

        return $response;


    }


    public function getOrderList()
    {
        try {
            $response = Http::redProvider()->get('/api/v1/orders');

            if (!$response->successful()) {
                //add custom codes
                throw new HttpException(400, 'Bad Request from RedProvider.');
            }
        } catch (Throwable $e) {
            Log::warning('RedProvider not reachable', [
                'error' => $e->getMessage(),
                'endpoint' => '/api/v1/orders',
            ]);

            throw new RuntimeException('Failed to fetch orders from RedProvider.');
        }


        return $response;
    }
}
