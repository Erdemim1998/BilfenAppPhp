<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (env('APP_DEBUG', false)) {
            DB::listen(function ($query) {
                Log::info("Query Time: {$query->time}ms; Query: {$query->sql}; Bindings: " . implode(', ', $query->bindings));
            });
        }
    }
}
