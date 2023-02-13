<?php

namespace Dan\Shopify\Test;

use Dan\Shopify\Shopify;
use Illuminate\Support\Arr;
use Orchestra\Testbench\TestCase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

class FulfillmentOrdersApiTest extends TestCase
{
    /** @test */
    public function it_cancels_a_fulfillment_order(): void
    {
        Http::fake();
        (new Shopify('shop', 'token'))->fulfillment_orders(123)->cancel();

        Http::assertSent(function (Request $request) {
            return $request->url() == 'https://shop.myshopify.com/admin/fulfillment_orders/123/cancel.json'
                && $request->method() == 'POST';
        });
    }

    /** @test */
    public function it_closes_a_fulfillment_order(): void
    {
        Http::fake();

        (new Shopify('shop', 'token'))->fulfillment_orders(123)->close(
            ['message' => 'Close message']
        );

        Http::assertSent(function (Request $request) {
            return $request->url() == 'https://shop.myshopify.com/admin/fulfillment_orders/123/close.json'
                && $request->method() == 'POST'
                && Arr::get($request->data(), 'fulfillment_order.message') === 'Close message';
        });
    }

    /** @test */
    public function it_gets_a_fulfillment_order(): void
    {
        Http::fake();

        (new Shopify('shop', 'token'))->fulfillment_orders->find(123);

        Http::assertSent(function (Request $request) {
            return $request->url() == 'https://shop.myshopify.com/admin/fulfillment_orders/123.json'
                && $request->method() == 'GET';
        });
    }

    /** @test */
    public function it_gets_a_list_of_fulfillment_orders(): void
    {
        Http::fake();

        (new Shopify('shop', 'token'))->fulfillment_orders->get();

        Http::assertSent(function (Request $request) {
            return $request->url() == 'https://shop.myshopify.com/admin/fulfillment_orders.json'
                && $request->method() == 'GET';
        });
    }

    /** @test */
    public function it_moves_a_fulfillment_order(): void
    {
        Http::fake();
        (new Shopify('shop', 'token'))->fulfillment_orders(123)->move();

        Http::assertSent(function (Request $request) {
            return $request->url() == 'https://shop.myshopify.com/admin/fulfillment_orders/123/move.json'
                && $request->method() == 'POST';
        });
    }

    /** @test */
    public function it_opens_a_fulfillment_order(): void
    {
        Http::fake();
        (new Shopify('shop', 'token'))->fulfillment_orders(123)->open();

        Http::assertSent(function (Request $request) {
            return $request->url() == 'https://shop.myshopify.com/admin/fulfillment_orders/123/open.json'
                && $request->method() == 'POST';
        });
    }

    /** @test */
    public function it_releases_a_fulfillment_order_hold(): void
    {
        Http::fake();
        (new Shopify('shop', 'token'))->fulfillment_orders(123)->release_hold();

        Http::assertSent(function (Request $request) {
            return $request->url() == 'https://shop.myshopify.com/admin/fulfillment_orders/123/release_hold.json'
                && $request->method() == 'POST';
        });
    }

    /** @test */
    public function it_reschedules_a_fulfillment_order(): void
    {
        Http::fake();
        (new Shopify('shop', 'token'))->fulfillment_orders(123)->reschedule();

        Http::assertSent(function (Request $request) {
            return $request->url() == 'https://shop.myshopify.com/admin/fulfillment_orders/123/reschedule.json'
                && $request->method() == 'POST';
        });
    }
}
