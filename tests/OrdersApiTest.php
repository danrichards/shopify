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

    /** /admin/orders/450789469/close.json */
    /** /admin/orders/450789469/open.json */
    /** /admin/orders/450789469/cancel.json */

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
}