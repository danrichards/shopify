<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Shopify Shop
    |--------------------------------------------------------------------------
    |
    | If your app is managing a single shop, you should configure it here.
    |
    | e.g. my-cool-store.myshopify.com
    */

    'shop'    => env('SHOPIFY_DOMAIN', ''),

    /*
    |--------------------------------------------------------------------------
    | Shopify Token
    |--------------------------------------------------------------------------
    |
    | Use of a token implies you've already proceeding to Shopify's Oauth flow
    | and have a token in your possession to make subsequent requests. See the
    | readme.md for help getting your token.
    */

    'token' => env('SHOPIFY_TOKEN', ''),

    /*
    |--------------------------------------------------------------------------
    | Options
    |--------------------------------------------------------------------------
    |
    | log_api_request_data:
    | When enabled will log the data of every API Request to shopify
    */

    'options' => [
        'log_api_request_data' => env('SHOPIFY_OPTION_LOG_API_REQUEST', 0),
    ],
    
    'webhooks' => [
        /**
         * Do not forget to add 'webhook/*' to your VerifyCsrfToken middleware
         */
        'enabled' => env('SHOPIFY_WEBHOOKS_ENABLED', 1),
        'route_prefix' => env('SHOPIFY_WEBHOOKS_ROUTE_PREFIX', 'webhook/shopify'),
        'secret' => env('SHOPIFY_WEBHOOKS_SECRET'),
        'middleware' => Dan\Shopify\Integrations\Laravel\Http\WebhookMiddleware::class,
        'event_routing' => [
            'carts/create' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'carts/update' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'checkouts/create' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'checkouts/delete' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'checkouts/update' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'collection_listings/add' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'collection_listings/remove' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'collection_listings/update' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'collections/create' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'collections/delete' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'collections/update' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'customer_groups/create' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'customer_groups/delete' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'customer_groups/update' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'customers/create' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'customers/delete' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'customers/disable' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'customers/enable' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'customers/update' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'disputes/create' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'disputes/update' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'draft_orders/create' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'draft_orders/delete' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'draft_orders/update' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'fulfillment_events/create' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'fulfillment_events/delete' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'fulfillments/create' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'fulfillments/update' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'order_transactions/create' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'orders/cancelled' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'orders/create' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'orders/delete' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'orders/fulfilled' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'orders/paid' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'orders/partially_fulfilled' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'orders/updated' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'product_listings/add' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'product_listings/remove' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'product_listings/update' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'products/create' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'products/delete' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'products/update' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'refunds/create' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'shop/update' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'app/uninstalled' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'themes/create' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'themes/delete' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'themes/publish' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
            'themes/update' => Dan\Shopify\Integrations\Laravel\Events\WebhookEvent::class,
        ],
    ],
];
