<?php

namespace Dan\Shopify\Integrations\Laravel\Http;

use BadMethodCallException;
use Dan\Shopify\Integrations\Laravel\Events\Webhook;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Response;

/**
 * Class WebhookController
 */
class WebhookController
{
    /**
     * @param $topic
     * @param Request $request
     * @return JsonResponse
     * @throws BadMethodCallException
     */
    public function handle($topic, Request $request)
    {
        $class = config("shopify.webhooks.event_routing.{$topic}");

        \Log::debug(__METHOD__, compact('topic'));

        switch (true) {
            case empty($class):
                throw new BadMethodCallException("dan/shopify::event topic `{$topic}` class not configured.");
            case ! class_exists($class):
                throw new BadMethodCallException("dan/shopify::event topic `{$topic}` class does not exist.");
            default:
                event(new $class($topic, $request->all()));
        }

        return Response::json(['success' => true]);
    }
}
