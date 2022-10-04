<?php

namespace Dan\Shopify\Test;

use Throwable;
use Dan\Shopify\Shopify;
use Orchestra\Testbench\TestCase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

class RecurringApplicationChargesApiTest extends TestCase
{
    /**
     * Create a new recurring application charge.
     *
     * @test
     * @throws Throwable
     */
    public function it_creates_a_new_recurring_application_charge(): void
    {
        Http::fake([
            '*admin/recurring_application_charges.json' => $this->getResource(),
        ]);

        $response = (new Shopify('shop', 'token'))->recurring_application_charges->post([
            'name'       => 'Charge Name',
            'price'      => 15.99,
            'return_url' => 'https://phpunit-store.myshopifyapps.com',
            'test'       => true,
        ]);

        Http::assertSent(function (Request $request) {
            return $request->url() == 'https://shop.myshopify.com/admin/recurring_application_charges.json'
                && $request->method() == 'POST';
        });

        $this->assertEquals(123, $response['id']);
        $this->assertEquals('Charge Name', $response['name']);
    }

    /**
     * Get a list of recurring application charges.
     *
     * @test
     * @throws Throwable
     */
    public function it_gets_a_list_of_orders(): void
    {
        Http::fake();

        (new Shopify('shop', 'token'))->recurring_application_charges->get();

        Http::assertSent(function (Request $request) {
            return $request->url() == 'https://shop.myshopify.com/admin/recurring_application_charges.json'
                && $request->method() == 'GET';
        });
    }

    /**
     * Fetch a recurring application charge by id.
     *
     * @test
     * @throws Throwable
     */
    public function it_gets_a_recurring_application_charge(): void
    {
        Http::fake();

        (new Shopify('shop', 'token'))->recurring_application_charges->find(123);

        Http::assertSent(function (Request $request) {
            return $request->url() == 'https://shop.myshopify.com/admin/recurring_application_charges/123.json'
                && $request->method() == 'GET';
        });
    }

    /**
     * Get the resource response data for a recurring application charge.
     *
     * @return array
     */
    protected function getResource()
    {
        return [
            'activated_on'     => '2022-09-20T12:00:00-00:00',
            'billing_on'       => '2022-09-28T12:00:00-00:00',
            'cancelled_on'     => '2022-09-30T12:00:00-00:00',
            'capped_amount'    => '100',
            'confirmation_url' => 'https://jsmith.myshopify.com/admin/charges/confirm_recurring_application_charge?id=654381177&amp;signature=BAhpBHkQASc%3D--374c02da2ea0371b23f40781b8a6d5f4a520e77b',
            'created_at'       => '2022-09-20T12:00:00-00:00',
            'id'               => 123,
            'name'             => 'Charge Name',
            'price'            => '100.00',
            'return_url'       => 'http://super-duper.shopifyapps.com',
            'status'           => 'accepted',
            'terms'            => '$1 for 1000 emails',
            'test'             => null,
            'trial_days'       => 7,
            'trial_ends_on'    => '2022-09-27T12:00:00-00:00',
            'updated_at'       => '2022-09-20T12:00:00-00:00',
        ];
    }
}
