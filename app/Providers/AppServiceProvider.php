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
    // Secara paksa memberitahu Laravel untuk menggunakan view kita
    Paginator::defaultView('vendor.pagination.bootstrap-4');
    Paginator::defaultSimpleView('vendor.pagination.bootstrap-4');
    }
}