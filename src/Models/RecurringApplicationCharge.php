<?php

namespace Dan\Shopify\Models;

class RecurringApplicationCharge extends AbstractModel
{
    /**
     * Constant representing an active subscription status.
     */
    public const ACTIVE = 'active';

    /**
     * Constant representing a cancelled subscription status.
     */
    public const CANCELLED = 'cancelled';

    /**
     * Constant representing a declined subscription status.
     */
    public const DECLINED = 'declined';

    /**
     * Constant representing an expired subscription status.
     */
    public const EXPIRED = 'expired';

    /**
     * Constant representing a frozen subscription status.
     */
    public const FROZEN = 'frozen';

    /**
     * Constant representing a pending subscription status.
     */
    public const PENDING = 'pending';

    /** @var string */
    public static $resource_name = 'recurring_application_charge';

    /** @var string */
    public static $resource_name_many = 'recurring_application_charges';

    /**
     * The recurring application charge statuses.
     *
     * @var string[]
     */
    public static $statuses = [
        self::ACTIVE, self::CANCELLED, self::DECLINED,
        self::FROZEN, self::EXPIRED, self::PENDING,
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'activated_on'     => 'datetime',
        'billing_on'       => 'datetime',
        'cancelled_on'     => 'datetime',
        'capped_amount'    => 'float',
        'confirmation_url' => 'string',
        'created_at'       => 'datetime',
        'id'               => 'integer',
        'name'             => 'string',
        'price'            => 'float',
        'return_url'       => 'string',
        'status'           => 'string',
        'terms'            => 'string',
        'test'             => 'bool',
        'trial_days'       => 'integer',
        'trial_ends_on'    => 'datetime',
        'updated_at'       => 'datetime',
    ];
}
