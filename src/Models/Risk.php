<?php

namespace Dan\Shopify\Models;

/**
 * Class Risk
 *
 * @property int $id
 * @property int $order_id
 * @property int $checkout_id
 * @property string $message
 * @property string $merchant_message
 * @property string $recommendation
 * @property bool $display
 * @property string $source
 * @property bool $cause_cancel
 * @property float $score
 */
class Risk extends AbstractModel
{
    /** @var string $resource_name */
    public static $resource_name = 'risk';

    /** @var string $resource_name_many */
    public static $resource_name_many = 'risks';

    /** @var array $dates */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /** @var array $casts */
    protected $casts = [
        'order_id' => 'string',
        'source' => 'string',
        'score' => 'float',
        'recommendation' => 'string',
        'display' => 'bool',
        'cause_cancel' => 'bool',
        'message' => 'string',
        'merchant_message' => 'string'
    ];
}
