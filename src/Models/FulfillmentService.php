<?php

namespace Dan\Shopify\Models;

/**
 * Class FulfillmentService
 */
class FulfillmentService extends AbstractModel
{

    /** @var string $resource_name */
    public static $resource_name = 'fulfillment_service';

    /** @var string $resource_name_many */
    public static $resource_name_many = 'fulfillment_services';

    /** @var array $dates */
    protected $dates = [];

    /** @var array $casts */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'handle' => 'string',
        'email' => 'string',
        'include_pending_stock' => 'bool',
        'requires_shipping_method' => 'bool',
        'service_name' => 'string',
        'inventory_management' => 'bool',
        'tracking_support' => 'bool',
        'provider_id' => 'integer',
        'location_id' => 'integer',
    ];

}
