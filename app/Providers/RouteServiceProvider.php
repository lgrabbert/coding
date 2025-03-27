<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Called before routes are registered.
     */
    public function boot(): void
    {
        $this->routes(function () {
            // Load routes/api.php under /api prefix
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Load web routes
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
