<?php

namespace App\Jobs;

use App\Models\Order;
use App\Enums\OrderStatus;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckOrderStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    //how long does the asynchronous process take to complete the status check, optimize shorter intervall
    public $backoff = [10, 20, 40];

    public function __construct(
        protected Order $order
    ) {
    }

    public function handle(): void
    {
        Log::info('Start order status check');

        try {
            Log::info('Job attempt', [
                'attempt' => $this->attempts(),
                'maxAttempts' => count($this->backoff) + 1
            ]);

            if (env('RED_PROVIDER_USE_MOCK', false)) {
                $this->handleMockService();

                return;
            }

            Log::debug('order queue info', [
                'order' => $this->order->toArray(),
            ]);


            $response = Http::redProvider()
                ->get('/order/' . $this->order->redProviderPortalId);

            Log::debug('look at server for new status', [
               'response' => $response->body(),
            ]);

            if ($response->successful()) {
                Log::debug('success status', [
                    'response' => $response->body(),
                ]);
                $status = $response->json('status');

                if ($status === 'completed') {
                    $this->order->update(['status' => OrderStatus::COMPLETED]);
                } elseif ($status === 'processing') {
                    $this->order->update(['status' => OrderStatus::PROCESSING]);


                    self::dispatch($this->order)
                        ->onQueue('order-checks')
                        ->delay(now()->addMinutes(5));
                }
            } else {
                throw new Exception($response->body());
            }

        } catch (Exception $e) {
            Log::error('Order status check failed', [
                'order_id' => $this->order->id,
                'error' => $e->getMessage()
            ]);

            if ($this->attempts() >= $this->tries) {
                Log::critical('Final attempt failed for order', [
                    'order_id' => $this->order->id,
                    'last_status' => $this->order->status,
                    'error' => $e->getMessage()
                ]);
            }


            throw $e;
        }
    }

    private function handleMockService(): void
    {
        if ($this->order->status === OrderStatus::ORDERED) {
            $this->order->update(['status' => OrderStatus::PROCESSING]);

            self::dispatch($this->order)
                ->onQueue('order-checks')
                ->delay(now()->addMinutes(1));
        } elseif ($this->order->status === OrderStatus::PROCESSING) {
            $this->order->update(['status' => OrderStatus::COMPLETED]);
        }
    }
}
