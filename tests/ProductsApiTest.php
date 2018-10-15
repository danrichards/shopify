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
     * @throws \Dan\Shopify\Exceptions\InvalidOrMissingEndpointException
     * @throws \Dan\Shopify\Exceptions\ModelNotFoundException
     * @throws \ReflectionException
     */
    public function it_gets_a_product()
    {
        $api = \Dan\Shopify\Shopify::fake([
            new Response(200, [], ProductFactory::create())
        ]);

        $reponse = $api->products->find($product_id = 123);

        $this->assertEquals(200, $api->lastResponseStatusCode());
        $this->assertEquals(Product::class, get_class($reponse));
        $this->assertEquals('GET', $api->lastRequestMethod());
        $this->assertEquals('/admin/products/123.json', $api->lastRequestUri());
    }

    /**
     * POST /admin/products.json
     * Creates a new product.
     *
     * @test
     * @throws \Dan\Shopify\Exceptions\InvalidOrMissingEndpointException
     * @throws \ReflectionException
     */
    public function it_creates_a_new_product()
    {
        $api = \Dan\Shopify\Shopify::fake([
            new Response(201, [], ProductFactory::create())
        ]);

        $reponse = $api->products->post(json_decode('{
            "title": "Burton Custom Freestyle 151",
            "body_html": "<strong>Good snowboard!</strong>",
            "vendor": "Burton",
            "product_type": "Snowboard",
            "tags": "Barnes & Noble, John\'s Fav, &quot;Big Air&quot;"
          }', true));

        $this->assertEquals(201, $api->lastResponseStatusCode());
        $this->assertTrue(is_array($reponse));
        $this->assertEquals('POST', $api->lastRequestMethod());
        $this->assertEquals('/admin/products.json', $api->lastRequestUri());
    }
}