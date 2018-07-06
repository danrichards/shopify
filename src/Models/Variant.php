<?php

namespace Dan\Shopify\Models;

/**
 * Class Variant
 *
 * @property int $id
 * @property int $product_id
 * @property string $title
 * @property float $price
 * @property string $sku
 * @property int $position
 * @property string $inventory_policy
 * @property float $compare_at_price
 * @property string $fulfillment_service
 * @property string $inventory_management
 * @property string $option1
 * @property string $option2
 * @property string $option3
 * @property bool $taxable
 * @property string $barcode
 * @property int $grams
 * @property int $image_id
 * @property int $inventory_quantity
 * @property float $weight
 * @property string $weight_unit
 * @property int $inventory_item_id
 * @property string $tax_code
 * @property int $old_inventory_quantity
 * @property bool $requires_shipping
 * @property array $metafields
 * @property string $admin_graphql_api_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
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
