<?php

namespace Dan\Shopify\Helpers;

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
        if (in_array($method, ['increment', 'decrement'])) {
            return $this->$method(...$parameters);
        }

        return $this->client->$method(...$parameters);
    }
}
