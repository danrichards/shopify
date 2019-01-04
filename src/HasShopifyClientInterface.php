<?php

namespace Dan\Shopify;

/**
 * Interface ShopifyApiInterface
 */
interface HasShopifyClientInterface
{
    /** @return string */
    public function getShop();

    /** @return string */
    public function getToken();
}