<?php


namespace Dan\Shopify\Models;

/**
 * Class DiscountCode
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
class DiscountCode extends AbstractModel
{
    /** @var string $resource_name */
    public static $resource_name = 'discount_code';

    /** @var string $resource_name_many */
    public static $resource_name_many = 'discount_codes';
}
