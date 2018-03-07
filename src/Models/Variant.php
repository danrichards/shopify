<?php

namespace Dan\Shopify\Models;

/**
 * Class Variant
 */
class Variant extends AbstractModel
{

    /** @var string $resource_name */
    public static $resource_name = 'variant';

    /** @var string $resource_name_many */
    public static $resource_name_many = 'variants';

    /** @var array $dates */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /** @var array $casts */
    protected $casts = [
        'product_id' => 'string',
        'title' => 'string',
        'price' => 'float',
        'sku' => 'string',
        'position' => 'int',
        'inventory_policy' => 'string',
        'compare_at_price' => 'float',
        'fulfillment_service' => 'string',
        'option1' => 'string',
        'taxable' => 'bool',
        'grams' => 'int',
        'image_id' => 'string',
        'inventory_quantity' => 'int',
        'weight' => 'float',
        'weight_unit' => 'string',
        'inventory_item_id' => 'string',
        'old_inventory_quantity' => 'int',
        'requires_shipping' => 'bool',
        'metafields' => 'array'
    ];

}
