<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\NewsScrapingAPIService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(NewsScrapingAPIService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
