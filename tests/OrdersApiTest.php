<?php

namespace Dan\Shopify\Test;

use Dan\Shopify\Helpers\Testing\ModelFactory\OrderFactory;
use Dan\Shopify\Helpers\Testing\TransactionMock;
use Dan\Shopify\Models\Order;
use PHPUnit\Framework\TestCase;

class OrdersApiTest extends TestCase
{
    /**
     * GET /admin/orders.json
     * Retrieves a list of products.
     *
     * @test
     * @throws \Dan\Shopify\Exceptions\InvalidOrMissingEndpointException
     * @throws \ReflectionException
     */
    public function it_gets_a_list_of_orders()
    {
        $api = \Dan\Shopify\Shopify::fake([
            TransactionMock::create(OrderFactory::create(2))
        ]);

        $response = $api->orders->get();

        $this->assertEquals(200, $api->lastResponseStatusCode());
        $this->assertTrue(is_array($response));
        $this->assertEquals('GET', $api->lastRequestMethod());
        $this->assertEquals('/admin/orders.json', $api->lastRequestUri());
        $this->assertCount(2, $response);
    }

    /**
     * GET /admin/orders/count.json
     * Retrieve a count of all orders.
     *
     * @test
     * @throws \Dan\Shopify\Exceptions\InvalidOrMissingEndpointException
     * @throws \ReflectionException
     */
    public function it_gets_a_count_of_orders()
    {
        $api = \Dan\Shopify\Shopify::fake([
            TransactionMock::create('{ "count": 2 }')
        ]);

        $response = $api->orders->count();

        $this->assertEquals(200, $api->lastResponseStatusCode());
        $this->assertEquals('GET', $api->lastRequestMethod());
        $this->assertEquals('/admin/orders/count.json', $api->lastRequestUri());
        $this->assertEquals(2, $response);
    }

    /**
     * GET /admin/orders/123.json
     * Retrieves a single order.
     *
     * @test
     * @throws \Dan\Shopify\Exceptions\InvalidOrMissingEndpointException
     * @throws \Dan\Shopify\Exceptions\ModelNotFoundException
     * @throws \ReflectionException
     */
    public function it_gets_a_order()
    {
        $api = \Dan\Shopify\Shopify::fake([
            TransactionMock::create(OrderFactory::create())
        ]);

        $response = $api->orders->find($order_id = 123);

        $this->assertEquals(200, $api->lastResponseStatusCode());
        $this->assertEquals(Order::class, get_class($response));
        $this->assertEquals('GET', $api->lastRequestMethod());
        $this->assertEquals('/admin/orders/123.json', $api->lastRequestUri());
    }

    /**
     * POST /admin/orders.json
     * Creates a new order.
     *
     * @test
     * @throws \Dan\Shopify\Exceptions\InvalidOrMissingEndpointException
     * @throws \ReflectionException
     */
    public function it_creates_a_new_order()
    {
        $api = \Dan\Shopify\Shopify::fake([
            TransactionMock::create(OrderFactory::create(), 201)
        ]);

        $response = $api->orders->post(json_decode('{
            "line_items": [
              {
                "variant_id": 447654529,
                "quantity": 1
              }
            ]
          }', true));

        $this->assertEquals(201, $api->lastResponseStatusCode());
        $this->assertTrue(is_array($response));
        $this->assertEquals('POST', $api->lastRequestMethod());
        $this->assertEquals('/admin/orders.json', $api->lastRequestUri());
    }

    /**
     * PUT /admin/orders/123.json
     * Updates a order.
     *
     * @test
     * @throws \Dan\Shopify\Exceptions\InvalidOrMissingEndpointException
     * @throws \ReflectionException
     */
    public function it_updates_a_order()
    {
        $update = [
            'note' => 'New note'
        ];

        $api = \Dan\Shopify\Shopify::fake([
            TransactionMock::create(OrderFactory::create(1, $update))
        ]);

        $response = $api->orders(123)->put($update);

        $this->assertEquals(200, $api->lastResponseStatusCode());
        $this->assertEquals('PUT', $api->lastRequestMethod());
        $this->assertEquals('/admin/orders/123.json', $api->lastRequestUri());
        $this->assertTrue(is_array($response));
        $this->assertEquals($update['note'], $response['note']);
    }

    /**
     * DELETE /admin/orders/123.json
     * Delete a order
     *
     * @test
     * @throws \ReflectionException
     */
    public function it_deletes_a_order()
    {
        $api = \Dan\Shopify\Shopify::fake([
            TransactionMock::create()
        ]);

        $response = $api->orders(123)->delete();

        $this->assertEquals(200, $api->lastResponseStatusCode());
        $this->assertEquals('DELETE', $api->lastRequestMethod());
        $this->assertEquals('/admin/orders/123.json', $api->lastRequestUri());
        $this->assertTrue(empty($response));
    }

    /**
     * POST /admin/orders/123/close.json
     * Closes an order
     *
     * @test
     * @throws \Dan\Shopify\Exceptions\InvalidOrMissingEndpointException
     * @throws \ReflectionException
     */
    public function it_closes_an_order()
    {
        $api = \Dan\Shopify\Shopify::fake([
            TransactionMock::create(OrderFactory::create(1, ['id' => 123]), 201)
        ]);

        $response = $api->orders(123)->post([], 'close');

        $this->assertEquals(201, $api->lastResponseStatusCode());
        $this->assertTrue(is_array($response));
        $this->assertEquals('POST', $api->lastRequestMethod());
        $this->assertEquals('/admin/orders/123/close.json', $api->lastRequestUri());
    }

    /** /admin/orders/450789469/open.json */
    /** /admin/orders/450789469/cancel.json */
}