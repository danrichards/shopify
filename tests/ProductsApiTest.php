<?php

namespace Dan\Shopify\Test;

use Dan\Shopify\Helpers\Testing\ModelFactory\ProductFactory;
use Dan\Shopify\Models\Product;
use Dan\Shopify\Shopify;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase;
use Throwable;

class ProductsApiTest extends TestCase
{
    /**
     * GET /admin/products.json
     * Retrieves a list of products.
     *
     * @test
     * @throws Throwable
     */
    public function it_gets_a_list_of_products()
    {
        Http::fake([
            '*admin/products.json' => ProductFactory::create(2)
        ]);

        $response = (new Shopify('shop', 'token'))->products->get();

        Http::assertSent(function (Request $request) {
            return $request->url() == 'https://shop.myshopify.com/admin/products.json'
                && $request->method() == 'GET';
        });

        $this->assertCount(2, $response);
    }

    /**
     * GET /admin/products/count.json
     * Retrieve a count of all products.
     *
     * @test
     * @throws Throwable
     */
    public function it_gets_a_count_of_products()
    {
        Http::fake([
            '*admin/products/count.json' => ['count' => 2]
        ]);

        $response = (new Shopify('shop', 'token'))->products->count();

        Http::assertSent(function (Request $request) {
            return $request->url() == 'https://shop.myshopify.com/admin/products/count.json'
                && $request->method() == 'GET';
        });

        $this->assertEquals(2, $response);
    }

    /**
     * GET /admin/products/123.json
     * Retrieves a single product.
     *
     * @test
     * @throws Throwable
     */
    public function it_gets_a_product()
    {
        Http::fake([
            '*admin/products/123.json' => ProductFactory::create(1, ['id' => 123])
        ]);

        $response = (new Shopify('shop', 'token'))->products->find(123);

        Http::assertSent(function (Request $request) {
            return $request->url() == 'https://shop.myshopify.com/admin/products/123.json'
                && $request->method() == 'GET';
        });

        $this->assertEquals(123, $response['id']);
    }

    /**
     * POST /admin/products.json
     * Creates a new product.
     *
     * @test
     * @throws Throwable
     */
    public function it_creates_a_new_product()
    {
        Http::fake([
            '*admin/products.json' => ProductFactory::create(1, ['id' => 123, 'title' => 'some title'])
        ]);

        $response = (new Shopify('shop', 'token'))->products->post([
            'title' => 'some title'
        ]);

        Http::assertSent(function (Request $request) {
            return $request->url() == 'https://shop.myshopify.com/admin/products.json'
                && $request->method() == 'POST';
        });

        $this->assertEquals(123, $response['id']);
        $this->assertEquals('some title', $response['title']);
    }

    /**
     * PUT /admin/products/123.json
     * Updates a product and its variants and images.
     *
     * @test
     * @throws Throwable
     */
    public function it_updates_a_product()
    {
        Http::fake([
            '*admin/products/123.json' => ProductFactory::create(1, ['title' => 'new title'])
        ]);

        $response = (new Shopify('shop', 'token'))->products(123)->put([
            'title' => 'new title'
        ]);

        Http::assertSent(function (Request $request) {
            return $request->url() == 'https://shop.myshopify.com/admin/products/123.json'
                && $request->method() == 'PUT';
        });

        $this->assertEquals('new title', $response['title']);
    }

    /**
     * DELETE /admin/products/123.json
     * Delete a product along with all its variants and images.
     *
     * @test
     * @throws Throwable
     */
    public function it_deletes_a_product()
    {
        Http::fake();

        (new Shopify('shop', 'token'))->products(123)->delete();

        Http::assertSent(function (Request $request) {
            return $request->url() == 'https://shop.myshopify.com/admin/products/123.json'
                && $request->method() == 'DELETE';
        });
    }
}
