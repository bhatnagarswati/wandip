<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        defined('CURL_SSLVERSION_DEFAULT') || define('CURL_SSLVERSION_DEFAULT', 0);
defined('CURL_SSLVERSION_TLSv1')   || define('CURL_SSLVERSION_TLSv1', 1);
defined('CURL_SSLVERSION_SSLv2')   || define('CURL_SSLVERSION_SSLv2', 2);
defined('CURL_SSLVERSION_SSLv3')   || define('CURL_SSLVERSION_SSLv3', 3);
        Cashier::useCurrency(config('cart.currency'), config('cart.currency_symbol'));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
