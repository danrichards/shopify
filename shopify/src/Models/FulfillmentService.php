<?php

namespace Dan\Shopify\Models;

/**
 * Class FulfillmentService.
 *
 * @property int $id
 * @property string $name
 * @property string $handle
 * @property string $email
 * @property bool $include_pending_stock
 * @property bool $requires_shipping_method
 * @property string $service_name
 * @property bool $inventory_management
 * @property bool $tracking_support
 * @property int $provider_id
 * @property int $location_id
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
        'id'                       => 'integer',
        'name'                     => 'string',
        'handle'                   => 'string',
        'email'                    => 'string',
        'include_pending_stock'    => 'bool',
        'requires_shipping_method' => 'bool',
        'service_name'             => 'string',
        'inventory_management'     => 'bool',
        'tracking_support'         => 'bool',
        'provider_id'              => 'integer',
        'location_id'              => 'integer',
    ];
}
