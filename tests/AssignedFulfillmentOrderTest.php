<?php

namespace Dan\Shopify\Test;

use PHPUnit\Framework\TestCase;
use Dan\Shopify\Models\AssignedFulfillmentOrder;

class AssignedFulfillmentOrderTest extends TestCase
{
    /** @test */
    public function it_casts_all_attributes_to_their_native_types(): void
    {
        $model = $this->getModel();

        $this->assertIsInt($model->id);
        $this->assertIsInt($model->shop_id);
        $this->assertIsInt($model->assigned_location_id);
    }

    /**
     * Make a new AssignedFulfillmentOrder instance.
     *
     * @return \Dan\Shopify\Models\AssignedFulfillmentOrder
     */
    protected function getModel()
    {
        return new AssignedFulfillmentOrder([
            'assigned_location_id' => '3183479',
            'destination'          => [
                'id'         => 54433189,
                'address1'   => '123 Amoebobacterieae St',
                'address2'   => 'Unit 806',
                'city'       => 'Ottawa',
                'company'    => '',
                'country'    => 'Canada',
                'email'      => 'bob@example.com',
                'first_name' => 'Bob',
                'last_name'  => 'Bobsen',
                'phone'      => '(555)555-5555',
                'province'   => 'Ontario',
                'zip'        => 'K2P0V6',
            ],
            'id'         => '255858046',
            'line_items' => [
                [
                    'id'                   => 466157049,
                    'shop_id'              => 3998762,
                    'fulfillment_order_id' => 1568020,
                    'line_item_id'         => 466157049,
                    'inventory_item_id'    => 6588097,
                    'quantity'             => 1,
                    'fulfillable_quantity' => 1,
                ],
            ],
            'order_id'       => 3183479,
            'request_status' => 'unsubmitted',
            'shop_id'        => 255858046,
            'status'         => 'open',
        ]);
    }
}
