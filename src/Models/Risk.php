<?php

namespace Dan\Shopify\Models;

/**
 * Class Risk
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
