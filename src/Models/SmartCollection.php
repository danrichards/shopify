<?php


namespace Dan\Shopify\Models;

use Carbon\Carbon;

/**
 * Class SmartCollection
 * @package Dan\Shopify\Models
 *
 * @property int $id
 * @property string|null $body_html
 * @property string $handle
 * @property array|null $image
 * @property array $rules
 * @property bool $disjunctive
 * @property string $sort_order
 * @property string|null $template_suffix
 * @property int $products_manually_sorted_count
 * @property string|null $published_scope
 * @property Carbon|null $published_at
 * @property Carbon|null $updated_at
 *
 */
class SmartCollection extends AbstractModel
{
    /** @var string $resource_name */
    public static $resource_name = 'smart_collection';

    /** @var string $resource_name_many */
    public static $resource_name_many = 'smart_collections';
}
