<?php

namespace Dan\Shopify\Integrations\Laravel\Http;

use BadMethodCallException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Response;

/**
 * Class WebhookController.
 */
class WebhookController
{
    /**
     * @param $topic
     * @param Request $request
     *
     * @throws BadMethodCallException
     *
     * @return JsonResponse
     */
    public function handle($topic, Request $request)
    {
        $class = config("shopify.webhooks.event_routing.{$topic}");

        $shop = static::getShop($request);

        switch (true) {
            case $class === false:
                //Do Nothing
                break;
            case empty($class):
                throw new BadMethodCallException("config/shopify@event_routing topic `{$topic}` class not configured.");
            case ! class_exists($class):
                throw new BadMethodCallException("config/shopify@event_routing topic `{$topic}` class does not exist.");
            default:
                event(new $class($topic, $request->all(), $shop));
        }

        return Response::json(['success' => true]);
    }

    /**
     * @return string
     */
    protected static function getShop(Request $request)
    {
        return $request->headers->get('x-shopify-shop-domain');
    }
}
