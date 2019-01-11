<?php

namespace Dan\Shopify\Models;

use Carbon\Carbon;

/**
 * Class Customer
 *
 * @property int $id
 * @property bool $accepts_marketing
 * @property array $addresses
 * @property string $address1
 * @property string $address2
 * @property string $city
 * @property string $company
 * @property string $country
 * @property string $country_name
 * @property Carbon $created_at
 * @property string $currency
 * @property object $default_address
 * @property string $email
 * @property string $first_name
 * @property int $last_order_id
 * @property string $last_order_name
 * @property string $last_name
 * @property string|null $multipass_identifier
 * @property int $orders_count
 * @property string|null $note
 * @property string|null $phone
 * @property string $province_code
 * @property string $state
 * @property string $tags
 * @property bool $tax_exempt
 * @property float $total_spent
 * @property Carbon $updated_at
 * @property bool $verified_email
 * @property string $zip
 */
class Customer extends AbstractModel
{
    /** @var string $resource_name */
    public static $resource_name = 'customer';

    /** @var string $resource_name_many */
    public static $resource_name_many = 'customers';

    /** @var array $dates */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /** @var array $casts */
    protected $casts = [
        'accepts_marketing' => 'bool',
        'addresses' => 'array',
        'default_address' => 'object',
        'orders_count' => 'integer',
        'tax_exempt' => 'bool',
        'total_spent' => 'float',
        'verified_email' => 'bool',
    ];
}
