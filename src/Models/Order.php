<?php

namespace Dan\Shopify\Models;

/**
 * Class Order
 */
class Order extends AbstractModel
{

    /** @var string $resource_name */
    public static $resource_name = 'order';

    /** @var string $resource_name_many */
    public static $resource_name_many = 'orders';

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
        'billing_address' => 'object',
        'tax_lines' => 'array',
        'fulfillments' => 'array',
        'refunds' => 'array',
        'customer' => 'object',
    ];

    // Financial statuses from Shopify
    const FINANCIAL_STATUS_AUTHORIZED = 'authorized';
    const FINANCIAL_STATUS_PAID = 'paid';
    const FINANCIAL_STATUS_PARTIALLY_PAID = 'partially_paid';
    const FINANCIAL_STATUS_PARTIALLY_REFUNDED = 'partially_refunded';
    const FINANCIAL_STATUS_PENDING= 'pending';
    const FINANCIAL_STATUS_REFUNDED = 'refunded';
    const FINANCIAL_STATUS_VOIDED = 'voided';

    /** @var array $financial_statuses */
    public static $financial_statuses = [
        self::FINANCIAL_STATUS_AUTHORIZED,
        self::FINANCIAL_STATUS_PAID,
        self::FINANCIAL_STATUS_PARTIALLY_PAID,
        self::FINANCIAL_STATUS_PARTIALLY_REFUNDED,
        self::FINANCIAL_STATUS_PENDING,
        self::FINANCIAL_STATUS_REFUNDED,
        self::FINANCIAL_STATUS_VOIDED
    ];

    // Fulfillment statuses from Shopify
    const FULFILLMENT_STATUS_FILLED = 'fulfilled';
    const FULFILLMENT_STATUS_PARTIAL = 'partial';
    const FULFILLMENT_STATUS_UNFILLED = null;

    /** @var array $fulfillment_statuses */
    public static $fulfillment_statuses = [
        self::FULFILLMENT_STATUS_FILLED,
        self::FULFILLMENT_STATUS_PARTIAL,
        self::FULFILLMENT_STATUS_UNFILLED
    ];

    // Risk recommendations from Shopify
    const RISK_RECOMMENDATION_LOW = 'accept';
    const RISK_RECOMMENDATION_MEDIUM = 'investigate';
    const RISK_RECOMMENDATION_HIGH = 'cancel';

    /** @var array $risk_statuses */
    public static $risk_statuses = [
        self::RISK_RECOMMENDATION_LOW,
        self::RISK_RECOMMENDATION_MEDIUM,
        self::RISK_RECOMMENDATION_HIGH
    ];

}
