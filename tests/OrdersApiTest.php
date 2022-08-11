<?php

namespace Dan\Shopify\Test;

use Dan\Shopify\Shopify;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase;
use Throwable;

class OrdersApiTest extends TestCase
{
    /**
     * GET /admin/orders.json
     * Retrieves a list of products.
     *
     * @test
     * @throws Throwable
     */
    public function it_gets_a_list_of_orders()
    {
        Http::fake();

        (new Shopify('shop', 'token'))->orders->get();

        Http::assertSent(function (Request $request) {
            return $request->url() == 'https://shop.myshopify.com/admin/orders.json'
                && $request->method() == 'GET';
        });
    }

    /**
     * GET /admin/orders/count.json
     * Retrieve a count of all orders.
     *
     * @test
     * @throws Throwable
     */
    public function it_gets_a_count_of_orders()
    {
        Http::fake([
            'https://shop.myshopify.com/admin/orders/count.json' => Http::response(['count' => 2])
        ]);

        (new Shopify('shop', 'token'))->orders->count();

        Http::assertSent(function (Request $request) {
            return $request->url() == 'https://shop.myshopify.com/admin/orders/count.json'
                && $request->method() == 'GET';
        });
    }

    /**
     * GET /admin/orders/123.json
     * Retrieves a single order.
     *
     * @test
     * @throws Throwable
     */
    public function it_gets_an_order()
    {
        Http::fake();

        (new Shopify('shop', 'token'))->orders->find(123);

        Http::assertSent(function (Request $request) {
            return $request->url() == 'https://shop.myshopify.com/admin/orders/123.json'
                && $request->method() == 'GET';
        });
    }

    /**
     * POST /admin/orders.json
     * Creates a new order.
     *
     * @test
     * @throws Throwable
     */
    public function it_creates_a_new_order()
    {
        Http::fake();

        (new Shopify('shop', 'token'))->orders->post($order = [
            'key1' => 'value1'
        ]);

        Http::assertSent(function (Request $request) use ($order) {
            return $request->url() == 'https://shop.myshopify.com/admin/orders.json'
                && $request->method() == 'POST'
                && $request->data() == compact('order');
        });
    }

    /**
     * PUT /admin/orders/123.json
     * Updates a order.
     *
     * @test
     * @throws Throwable
     */
    public function it_updates_a_order()
    {
        Http::fake();

        (new Shopify('shop', 'token'))->orders(123)->put($order = [
            'key1' => 'value1'
        ]);

        $order['id'] = 123;

        Http::assertSent(function (Request $request) use ($order) {
            return $request->url() == 'https://shop.myshopify.com/admin/orders/123.json'
                && $request->method() == 'PUT'
                && $request->data() == compact('order');
        });
    }

    /**
     * DELETE /admin/orders/123.json
     * Delete a order.
     *
     * @test
     * @throws Throwable
     */
    public function it_deletes_a_order()
    {
        Http::fake();

        (new Shopify('shop', 'token'))->orders(123)->delete();

        Http::assertSent(function (Request $request) {
            return $request->url() == 'https://shop.myshopify.com/admin/orders/123.json'
                && $request->method() == 'DELETE';
        });
    }

    /**
     * POST /admin/orders/123/close.json
     * Closes an order.
     *
     * @test
     * @throws Throwable
     */
    public function it_closes_an_order()
    {
        Http::fake();

        (new Shopify('shop', 'token'))->orders(123)->post([], 'close');

        Http::assertSent(function (Request $request) {
            return $request->url() == 'https://shop.myshopify.com/admin/orders/123/close.json'
                && $request->method() == 'POST';
        });
    }
}
