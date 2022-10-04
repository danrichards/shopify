<?php

namespace Dan\Shopify\Test;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Dan\Shopify\Models\RecurringApplicationCharge;

class RecurringApplicationChargeTest extends TestCase
{
    /** @test */
    public function it_casts_all_attributes_to_their_native_types()
    {
        $model = $this->getModel();

        $this->assertIsFloat($model->capped_amount);
        $this->assertIsInt($model->id);
        $this->assertIsFloat($model->price);
        $this->assertIsInt($model->trial_days);

        $this->assertInstanceOf(Carbon::class, $model->activated_on);
        $this->assertInstanceOf(Carbon::class, $model->billing_on);
        $this->assertInstanceOf(Carbon::class, $model->cancelled_on);
        $this->assertInstanceOf(Carbon::class, $model->created_at);
        $this->assertInstanceOf(Carbon::class, $model->trial_ends_on);
        $this->assertInstanceOf(Carbon::class, $model->updated_at);
    }

    /**
     * Make a new RecurringApplicationCharge instance.
     *
     * @return \Dan\Shopify\Models\RecurringApplicationCharge
     */
    protected function getModel()
    {
        return new RecurringApplicationCharge([
            'activated_on' => '2022-09-20T12:00:00-00:00',
            'billing_on' => '2022-09-28T12:00:00-00:00',
            'cancelled_on' => '2022-09-30T12:00:00-00:00',
            'capped_amount' => "100",
            'confirmation_url' => "https://jsmith.myshopify.com/admin/charges/confirm_recurring_application_charge?id=654381177&amp;signature=BAhpBHkQASc%3D--374c02da2ea0371b23f40781b8a6d5f4a520e77b",
            'created_at' => '2022-09-20T12:00:00-00:00',
            'id' => 675931192,
            'name' => "Super Duper Expensive action",
            'price' => "100.00",
            'return_url' => "http://super-duper.shopifyapps.com",
            'status' => "accepted",
            'terms' => "$1 for 1000 emails",
            'test' => null,
            'trial_days' => 7,
            'trial_ends_on' => '2022-09-27T12:00:00-00:00',
            'updated_at' => '2022-09-20T12:00:00-00:00'
        ]);
    }
}
