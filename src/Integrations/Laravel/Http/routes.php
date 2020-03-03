<?php

Route::post('/{topic?}', 'WebhookController@handle')
    ->where('topic', '(.*)')
    ->name('shopify-webhook');
