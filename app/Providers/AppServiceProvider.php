<?php

namespace App\Providers;

use App\Services\SMS\Ghasedak;
use App\Services\SMS\SmsServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SmsServiceInterface::class, Ghasedak::class);
//        $this->app->bind(SmsServiceInterface::class, KavehNegar::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
