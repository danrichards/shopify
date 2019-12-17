<?php


namespace Dan\Shopify\Models;

use Carbon\Carbon;

/**
 * Class PriceRule
 * @package Dan\Shopify\Models
 *
 * @property int    $id
 * @property int    $allocation_limit
 * @property string $allocation_method
 * @property string $customer_selection
 * @property array  $entitled_collection_ids
 * @property array  $entitled_country_ids
 * @property array  $entitled_product_ids
 * @property array  $entitled_variant_ids
 * @property bool   $once_per_customer
 * @property array  $prerequisite_collection_ids
 * @property array  $prerequisite_customer_ids
 * @property array  $prerequisite_product_ids
 * @property array  $prerequisite_quantity_range
 * @property array  $prerequisite_saved_search_ids
 * @property array  $prerequisite_shipping_price_range
 * @property array  $prerequisite_subtotal_range
 * @property array  $prerequisite_to_entitlement_quantity_ratio
 * @property array  $prerequisite_variant_ids
 * @property string $target_selection
 * @property string $target_type
 * @property int    $usage_limit
 * @property int    $value
 * @property string $value_type
 * @property Carbon $starts_at
 * @property Carbon $ends_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class PriceRule
{
    /** @var string $resource_name */
    public static $resource_name = 'price_rule';

    /** @var string $resource_name_many */
    public static $resource_name_many = 'price_rules';
}
