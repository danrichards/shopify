<?php

namespace Dan\Shopify\Models;

use Carbon\Carbon;

/**
 * Class SmartCollection.
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
 */
class SmartCollection extends AbstractModel
{
    /** @var string $resource_name */
    public static $resource_name = 'smart_collection';

    /** @var string $resource_name_many */
    public static $resource_name_many = 'smart_collections';

    /** Published Scope constants */
    public const PUBLISHED_SCOPE_GLOBAL = 'global';
    public const PUBLISHED_SCOPE_WEB = 'web';

    /** Sort Order constants */
    public const SORT_ORDER_ALPHA_ASC = 'alpha-asc';
    public const SORT_ORDER_ALPHA_DES = 'alpha-des';
    public const SORT_ORDER_BEST_SELLING = 'best-selling';
    public const SORT_ORDER_CREATED = 'created';
    public const SORT_ORDER_CREATED_DESC = 'created-desc';
    public const SORT_ORDER_MANUAL = 'manual';
    public const SORT_ORDER_PRICE_ASC = 'price-asc';
    public const SORT_ORDER_PRICE_DESC = 'price-desc';

    /** @var array $public_scopes */
    public static $public_scopes = [
        self::PUBLISHED_SCOPE_GLOBAL,
        self::PUBLISHED_SCOPE_WEB,
    ];

    /** @var array $sort_orders */
    public static $sort_orders = [
        self::SORT_ORDER_ALPHA_ASC,
        self::SORT_ORDER_ALPHA_DES,
        self::SORT_ORDER_BEST_SELLING,
        self::SORT_ORDER_CREATED,
        self::SORT_ORDER_CREATED_DESC,
        self::SORT_ORDER_MANUAL,
        self::SORT_ORDER_PRICE_ASC,
        self::SORT_ORDER_PRICE_DESC,
    ];

    /** @var array $casts */
    protected $casts = [
        'image' => 'array',
        'rules' => 'array',
    ];
}
