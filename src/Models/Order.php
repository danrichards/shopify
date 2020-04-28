<?php

namespace Dan\Shopify\Models;

/**
 * Class Order.
 *
 * @property int $id
 * @property string $email
 * @property \Carbon\Carbon $closed_at
 * @property int $number
 * @property string $note
 * @property string $token
 * @property string $gateway
 * @property bool $test
 * @property float $total_price
 * @property float $subtotal_price
 * @property int $total_weight
 * @property float $total_tax
 * @property bool $taxes_included
 * @property string $currency
 * @property string $financial_status
 * @property bool $confirmed
 * @property float $total_discounts
 * @property float $total_line_items_price
 * @property string $cart_token
 * @property bool $buyer_accepts_marketing
 * @property string $name
 * @property string $referring_site
 * @property string $landing_site
 * @property \Carbon\Carbon $cancelled_at
 * @property string $cancelled_reason
 * @property float $total_price_usd
 * @property string $checkout_token
 * @property string $reference
 * @property string $user_id
 * @property int $location_id
 * @property string $location_
 * @property string $source_identifier
 * @property string $source_url
 * @property \Carbon\Carbon $processed_at
 * @property string $device_id
 * @property string $phone
 * @property string $customer_locale
 * @property string $app_id
 * @property string $browser_ip
 * @property string $landing_site_ref
 * @property string $order_number
 * @property array $discount_applications
 * @property array $discount_codes
 * @property array $note_attributes
 * @property array $payment_gateway_names
 * @property string $processing_method
 * @property int $checkout_id
 * @property string $source_name
 * @property string $fulfillment_status
 * @property array $tax_lines
 * @property string $tags
 * @property string $contact_email
 * @property string $order_status_url
 * @property string $admin_graphql_api_id
 * @property array $line_items
 * @property array $shipping_lines
 * @property \stdClass $billing_address
 * @property \stdClass $shipping_address
 * @property array $fulfillments
 * @property \stdClass $client_details
 * @property array $refunds
 * @property \stdClass $payment_details
 * @property \stdClass $customer
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
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
        'processed_at',
    ];

    /** @var array $casts */
    protected $casts = [
        'test'                    => 'bool',
        'confirmed'               => 'bool',
        'total_price'             => 'float',
        'subtotal_price'          => 'float',
        'total_weight'            => 'float',
        'total_tax'               => 'float',
        'taxes_included'          => 'bool',
        'total_discounts'         => 'float',
        'total_line_items_price'  => 'float',
        'buyer_accepts_marketing' => 'float',
        'total_price_usd'         => 'float',
        'discount_codes'          => 'array',
        'note_attributes'         => 'array',
        'payment_gateway_names'   => 'array',
        'line_items'              => 'array',
        'shipping_lines'          => 'array',
        'shipping_address'        => 'object',
        'billing_address'         => 'object',
        'tax_lines'               => 'array',
        'fulfillments'            => 'array',
        'refunds'                 => 'array',
        'customer'                => 'object',
        'client_details'          => 'object',
        'payment_details'         => 'object',
    ];

    // Financial statuses from Shopify
    const FINANCIAL_STATUS_AUTHORIZED = 'authorized';
    const FINANCIAL_STATUS_PAID = 'paid';
    const FINANCIAL_STATUS_PARTIALLY_PAID = 'partially_paid';
    const FINANCIAL_STATUS_PARTIALLY_REFUNDED = 'partially_refunded';
    const FINANCIAL_STATUS_PENDING = 'pending';
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
        self::FINANCIAL_STATUS_VOIDED,
    ];

    // Fulfillment statuses from Shopify
    const FULFILLMENT_STATUS_FILLED = 'fulfilled';
    const FULFILLMENT_STATUS_PARTIAL = 'partial';
    const FULFILLMENT_STATUS_UNFILLED = null;

    /** @var array $fulfillment_statuses */
    public static $fulfillment_statuses = [
        self::FULFILLMENT_STATUS_FILLED,
        self::FULFILLMENT_STATUS_PARTIAL,
        self::FULFILLMENT_STATUS_UNFILLED,
    ];

    // Risk recommendations from Shopify
    const RISK_RECOMMENDATION_LOW = 'accept';
    const RISK_RECOMMENDATION_MEDIUM = 'investigate';
    const RISK_RECOMMENDATION_HIGH = 'cancel';

    /** @var array $risk_statuses */
    public static $risk_statuses = [
        self::RISK_RECOMMENDATION_LOW,
        self::RISK_RECOMMENDATION_MEDIUM,
        self::RISK_RECOMMENDATION_HIGH,
    ];

    const FILTER_STATUS_ANY = 'any';
    const FILTER_STATUS_CANCELLED = 'cancelled';
    const FILTER_STATUS_CLOSED = 'closed';
    const FILTER_STATUS_OPEN = 'open';

    /** @var array $filter_statuses */
    public static $filter_statuses = [
        self::FILTER_STATUS_ANY,
        self::FILTER_STATUS_CANCELLED,
        self::FILTER_STATUS_CLOSED,
        self::FILTER_STATUS_OPEN,
    ];
}
