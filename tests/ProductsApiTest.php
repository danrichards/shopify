<?php

namespace Dan\Shopify\Test;

use Dan\Shopify\Helpers\Testing\ModelFactory\ProductFactory;
use Dan\Shopify\Models\Product;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class ProductsApiTest extends TestCase
{
    /**
     * GET /admin/products/123.json
     * Retrieves a single product.
     *
     * @test
     */
    public function it_gets_a_product_from_shopify()
    {

        $api = \Dan\Shopify\Shopify::fake([
            new Response(200, [], ProductFactory::create())
        ]);

        $reponse = $api->products->find($product_id = 123);

        $this->assertEquals(Product::class, get_class($reponse));
        $this->assertEquals('GET', $api->lastRequestMethod());
        $this->assertEquals('/admin/products/123.json', $api->lastRequestUri());
    }
}