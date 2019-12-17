<?php


namespace Dan\Shopify\Models;

/**
 * Class Discount
 * @package Dan\Shopify\Models
 *
 * Table properties
 * @property int    $id
 * @property string $code
 * @property int    $price_rule_id
 * @property int    $usage_count
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Discount
{
    /** @var string $resource_name */
    public static $resource_name = 'discount';

    /** @var string $resource_name_many */
    public static $resource_name_many = 'discounts';
}
