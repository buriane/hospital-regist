<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(\Milon\Barcode\BarcodeServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->alias('DNS1D', \Milon\Barcode\Facades\DNS1DFacade::class);
        $this->app->alias('DNS2D', \Milon\Barcode\Facades\DNS2DFacade::class);
        Carbon::setLocale('id');
    }
}
