<?php

declare(strict_types=1);

namespace App\Providers;

use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;
use RuntimeException;


class HttpMacrosProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Http::macro('redProvider', function () {
            if (env('RED_PROVIDER_USE_MOCK', false)) {
                return Http::fake([
                    '*' => Http::response([
                        'status' => 'completed'
                    ], 200)
                ]);
            }

            $baseUrl = env('RED_PROVIDER_URL');
            $clientId = env('RED_PROVIDER_CLIENT_ID');
            $clientSecret = env('RED_PROVIDER_CLIENT_SECRET');
            $certPath = base_path(env('RED_PROVIDER_CERT_PATH', 'routes/ssl_cert.pem'));

            if (!Cache::has('redprovider.token')) {
                $response = Http::withOptions([
                    RequestOptions::VERIFY => $certPath,
                ])->post($baseUrl . "token", [
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                ]);

                if (!$response->successful()) {
                    throw new RuntimeException('Failed to retrieve RedProvider token: ' . $response->body());
                }

                $json = $response->json();
                $ttl = $json['ttl'] ?? 60;

                Cache::put('redprovider.token', $json['access_token'], now()->addSeconds($ttl));
            }

            $token = Cache::get('redprovider.token');

            return Http::withOptions([
                RequestOptions::VERIFY => $certPath,
            ])
                ->retry(3, 1000)
                ->withToken($token)
                ->baseUrl($baseUrl);
        });
    }
}
