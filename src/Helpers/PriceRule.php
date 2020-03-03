<?php

namespace Dan\Shopify\Helpers;

use Dan\Shopify\Exceptions\InvalidOrMissingEndpointException;

class PriceRule extends Endpoint
{
    /**
     * @param string $endpoint
     *
     * @throws InvalidOrMissingEndpointException
     *
     * @return $this|Endpoint
     */
    public function __get($endpoint)
    {
        if ($endpoint == 'discount_codes') {
            $client = $this->client;

            if (empty($client->ids)) {
                throw new InvalidOrMissingEndpointException('The discount_codes endpoint on price_rules requires a price_rule ID. e.g. $api->price_rules(123)->discount_codes->get()');
            }

            $client->queue[] = [$client->api, $client->ids[0] ?? null];
            $client->api = 'discount_codes';
            $client->ids = [];

            return $this;
        } else {
            return parent::__get($endpoint);
        }
    }
}
