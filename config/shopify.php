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
        'log_api_request_data' => true,
    ],
];
