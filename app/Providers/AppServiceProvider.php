<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; // <-- PASTIKAN BARIS INI ADA

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
        // SECARA SPESIFIK MEMBERITAHU LARAVEL UNTUK MENGGUNAKAN GAYA BOOTSTRAP 4
        Paginator::useBootstrapFour();
    }
}