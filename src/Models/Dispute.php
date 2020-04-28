<?php

namespace Dan\Shopify\Models;

/**
 * Class Dispute.
 *
 * @property int $id
 * @property int $order_id
 * @property string $type
 * @property string $currency
 * @property float $amount
 * @property string $reason
 * @property string $network_reason_code
 * @property string $status
 * @property \Carbon\Carbon $evidence_due_by
 * @property \Carbon\Carbon $evidence_sent_on
 * @property \Carbon\Carbon $finalized_on
 * @property \Carbon\Carbon $initiated_at
 */
class Dispute extends AbstractModel
{
    /** @var string $resource_name */
    public static $resource_name = 'dispute';

    /** @var string $resource_name_many */
    public static $resource_name_many = 'disputes';

    /** @var array $dates */
    protected $dates = [
        'evidence_due_by',
        'evidence_sent_on',
        'finalized_on',
        'initiated_at',
    ];

    /** @var array $casts */
    protected $casts = [
        'type'                => 'string',
        'currency'            => 'string',
        'amount'              => 'float',
        'reason'              => 'string',
        'network_reason_code' => 'string',
        'status'              => 'string',
    ];

    const TYPE_CHARGEBACK = 'chargeback';
    const TYPE_INQUIRY = 'inquiry';

    /** @var array $types */
    public static $types = [
        self::TYPE_CHARGEBACK,
        self::TYPE_INQUIRY,
    ];

    const REASON_BANK_NOT_PROCESS = 'bank_not_process';
    const REASON_CREDIT_NOT_PROCESSED = 'credit_not_processed';
    const REASON_CUSTOMER_INITIATED = 'customer_initiated';
    const REASON_DEBIT_NOT_AUTHORIZED = 'debit_not_authorized';
    const REASON_DUPLICATE = 'duplicate';
    const REASON_FRAUDULENT = 'fraudulent';
    const REASON_GENERAL = 'general';
    const REASON_INCORRECT_ACCOUNT_DETAILS = 'incorrect_account_details';
    const REASON_INSUFFICIENT_FUNDS = 'insufficient_funds';
    const REASON_PRODUCT_NOT_RECEIVED = 'product_not_received';
    const REASON_PRODUCT_UNACCEPTABLE = 'product_unacceptable';
    const REASON_SUBSCRIPTION_CANCELED = 'subscription_canceled';
    const REASON_UNRECOGNIZED = 'unrecognized';

    /** @var array $reasons */
    public static $reasons = [
        self::REASON_BANK_NOT_PROCESS,
        self::REASON_CREDIT_NOT_PROCESSED,
        self::REASON_CUSTOMER_INITIATED,
        self::REASON_DEBIT_NOT_AUTHORIZED,
        self::REASON_DUPLICATE,
        self::REASON_FRAUDULENT,
        self::REASON_GENERAL,
        self::REASON_INCORRECT_ACCOUNT_DETAILS,
        self::REASON_INSUFFICIENT_FUNDS,
        self::REASON_PRODUCT_NOT_RECEIVED,
        self::REASON_PRODUCT_UNACCEPTABLE,
        self::REASON_SUBSCRIPTION_CANCELED,
        self::REASON_UNRECOGNIZED,
    ];

    const STATUS_NEEDS_RESPONSE = 'needs_response';
    const STATUS_UNDER_REVIEW = 'under_review';
    const STATUS_CHARGE_REFUNDED = 'charge_refunded';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_WON = 'won';
    const STATUS_LOST = 'lost';

    /** @var array $statuses */
    public static $statuses = [
        self::STATUS_NEEDS_RESPONSE,
        self::STATUS_UNDER_REVIEW,
        self::STATUS_CHARGE_REFUNDED,
        self::STATUS_ACCEPTED,
        self::STATUS_WON,
        self::STATUS_LOST,
    ];
}
