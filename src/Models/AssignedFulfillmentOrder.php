<?php

namespace Dan\Shopify\Models;

class AssignedFulfillmentOrder extends AbstractModel
{
    /**
     * Constant representing the fulfillment service accepted the merchant's fulfillment request.
     */
    public const ACCEPTED = 'accepted';

    /**
     * Constant representing the fulfillment service accepted the merchant's fulfillment cancellation request.
     */
    public const CANCELLATION_ACCEPTED = 'cancellation_accepted';

    /**
     * Constant representing the fulfillment service rejected the merchant's fulfillment cancellation request.
     */
    public const CANCELLATION_REJECTED = 'cancellation_rejected';

    /**
     * Constant representing the merchant requested a cancellation of the fulfillment request for the fulfillment order.
     */
    public const CANCELLATION_REQUESTED = 'cancellation_requested';

    /**
     * Constant representing a fulfillment order has been cancelled by the merchant.
     */
    public const CANCELLED = 'cancelled';

    /**
     * Constant representing a fulfillment order has been completed and closed
     * or the fulfillment service closed the fulfillment order without completing it.
     */
    public const CLOSED = 'closed';

    /**
     * Constant representing a fulfillment order is being processed.
     */
    public const IN_PROGRESS = 'in_progress';

    /**
     * Constant representing a fulfillment order cannot be completed as requested.
     */
    public const INCOMPLETE = 'incomplete';

    /**
     * Constant representing the default state for newly created fulfillment orders.
     */
    public const OPEN = 'open';

    /**
     * Constant representing the fulfillment service rejected the merchant's fulfillment request.
     */
    public const REJECTED = 'rejected';

    /**
     * Constant representing the merchant requested fulfillment for the fulfillment order.
     */
    public const SUBMITTED = 'submitted';

    /**
     * Constant representing an initial request status for the newly-created fulfillment orders.
     */
    public const UNSUBMITTED = 'unsubmitted';

    /**
     * The fulfillment request statuses.
     *
     * @var string[]
     */
    public static $request_statuses = [
        self::ACCEPTED, self::CANCELLATION_ACCEPTED, self::CANCELLATION_REJECTED, self::CLOSED,
        self::CANCELLATION_REQUESTED, self::REJECTED, self::UNSUBMITTED, self::SUBMITTED,
    ];

    /** @var string */
    public static $resource_name = 'assigned_fulfillment_order';

    /** @var string */
    public static $resource_name_many = 'assigned_fulfillment_orders';

    /**
     * The fulfillment order statuses.
     *
     * @var string[]
     */
    public static $statuses = [
        self::CANCELLED, self::CLOSED,
        self::IN_PROGRESS, self::INCOMPLETE, self::OPEN,
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id'                   => 'integer',
        'shop_id'              => 'integer',
        'assigned_location_id' => 'integer',
    ];
}
