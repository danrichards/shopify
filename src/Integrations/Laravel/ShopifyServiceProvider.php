<?php

namespace Dan\Shopify\Integrations\Laravel;

use Dan\Shopify\Util;
use Illuminate\Support\ServiceProvider;
use Dan\Shopify\Shopify;

/**
 * Class ShopifyServiceProvider
 */
class ShopifyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../../config/shopify.php' => config_path('shopify.php')
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (Util::isLaravel()) {
            $this->mergeConfigFrom(
                __DIR__ . '/../../../config/shopify.php', 'shopify'
            );
        }

        $shop = config('shopify.shop');
        $token = config('shopify.token');

        if ($shop && $token) {
            $this->app->singleton('shopify', function ($app) use ($shop, $token) {
                return new Shopify($shop, $token);
            });
        }
    }
}
