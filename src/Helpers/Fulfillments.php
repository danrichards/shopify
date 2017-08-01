<?php

namespace Dan\Shopify\Helpers;

use App\Models\Fulfillment;
use Dan\Shopify\Models\AbstractModel;
use Dan\Shopify\Util;

/**
 * Class Orders
 */
class Fulfillments extends Endpoint
{

    /** @var int $order_id */
    protected $order_id;

    /**
     * @param int|string|array|\stdClass|\Dan\Shopify\Models\AbstractModel $order
     * @return $this
     */
    public function order($order)
    {
        $this->order_id = Util::getKeyFromMixed($order);
        $this->api->endpoint = "orders/{$this->order_id}/fulfillments";
        return $this;
    }

}
