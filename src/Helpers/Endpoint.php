<?php

namespace Dan\Shopify\Helpers;

use Dan\Shopify\Exceptions\InvalidOrMissingEndpointException;
use Dan\Shopify\Shopify;

/**
 * Class Endpoint.
 *
 * @mixin Shopify
 *
 * @property string endpoint
 * @property array ids
 */
abstract class Endpoint
{
    /** @var string[] $endpoints */
    protected static $endpoints = [
        'assets',
        'customers',
        'discount_codes',
        'disputes',
        'fulfillments',
        'fulfillment_services',
        'images',
        'metafields',
        'orders',
        'price_rules',
        'products',
        'risks',
        'smart_collections',
        'themes',
        'variants',
        'webhooks',
    ];

    /** @var Shopify $client */
    protected $client;

    /**
     * Endpoint constructor.
     *
     * @param Shopify $client
     */
    public function __construct(Shopify $client)
    {
        $this->client = $client;
    }

    /**
     * Set our endpoint by accessing it via a property.
     *
     * @param string $property
     *
     * @return $this
     */
    public function __get($property)
    {
        // If we're accessing another endpoint
        if (in_array($property, static::$endpoints)) {
            $client = $this->client;

            if (empty($client->ids)) {
                throw new InvalidOrMissingEndpointException('Calling ' . $method . ' from ' . $this->client->api . ' requires an id');
            }

            array_unshift($client->queue, [ $client->api, array_last($client->ids) ]);
            $client->api = $property;
            $client->ids = [];

            return $client->__get($property);
        }

        return $this->$property ?? $this->client->__get($property);
    }

    /**
     * Handle dynamic method calls into the model.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (in_array($method, static::$endpoints)) {
            if ($parameters === []) {
                throw new InvalidOrMissingEndpointException('Calling ' . $method . ' from ' . $this->client->api . ' requires an id');
            }

            array_unshift($this->client->queue, [ $this->client->api, array_last($this->client->ids) ]);
            $this->client->api = $method;
            $this->client->ids = [];

            return $this->client->$method(...$parameters);
        }

        if (in_array($method, ['increment', 'decrement'])) {
            return $this->$method(...$parameters);
        }

        return $this->client->$method(...$parameters);
    }
}
