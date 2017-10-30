<?php

namespace Dan\Shopify\Models;

/**
 * Class Fulfillment
 */
class Fulfillment extends AbstractModel
{

    /** @var string $resource_name */
    public static $resource_name = 'fulfillment';

    /** @var string $resource_name_many */
    public static $resource_name_many = 'fulfillments';

    /** @var array $dates */
    protected $dates = [
        'closed_at',
        'created_at',
        'updated_at',
        'cancelled_at',
        'processed_at'
    ];

    /** @var array $casts */
    protected $casts = [
        'test' => 'bool',
        'total_price' => 'float',
        'subtotal_price' => 'float',
        'total_weight' => 'float',
        'total_tax' => 'float',
        'taxes_included' => 'bool',
        'total_discounts' => 'float',
        'total_line_items_price' => 'float',
        'buyer_accepts_marketing' => 'float',
        'total_price_usd' => 'float',
        'discount_codes' => 'array',
        'note_attributes' => 'array',
        'payment_gateway_names' => 'array',
        'line_items' => 'array',
        'shipping_lines' => 'array',
        'shipping_address' => 'object',
        'billing_address' => 'object',
        'tax_lines' => 'array',
        'fulfillments' => 'array',
        'refunds' => 'array',
        'customer' => 'object',
    ];

}
