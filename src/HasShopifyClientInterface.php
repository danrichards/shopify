<?php

namespace Dan\Shopify;

/**
 * Interface HasShopifyClientInterface
 */
interface HasShopifyClientInterface
{
    /** @return string */
    public function getShop();

    /** @return string */
    public function getToken();
}