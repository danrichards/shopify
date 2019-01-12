<?php

namespace Dan\Shopify\Models;

/**
 * Class Product
 *
 * @property int $id
 * @property string $title
 * @property string $body_html
 * @property string $vendor
 * @property string $product_type
 * @property string $handle
 * @property \Carbon\Carbon $published_at
 * @property string $template_suffix
 * @property string $tags
 * @property string $published_scope
 * @property string $admin_graphql_api_id
 * @property array $variants
 * @property array $options
 * @property array $images
 * @property string $image
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Product extends AbstractModel
{
    /** @var string $resource_name */
    public static $resource_name = 'product';

    /** @var string $resource_name_many */
    public static $resource_name_many = 'products';

    /** @var array $dates */
    protected $dates = [
        'created_at',
        'updated_at',
        'published_at'
    ];

    /** @var array $casts */
    protected $casts = [
        'variants' => 'array',
        'options' => 'array',
        'images' => 'array',
        'image' => 'object'
    ];

    const PUBLISHED_SCOPE_GLOBAL = 'global';
    const PUBLISHED_SCOPE_WEB = 'web';

    /** @var array $published_scopes */
    public static $published_scopes = [
        self::PUBLISHED_SCOPE_GLOBAL,
        self::PUBLISHED_SCOPE_WEB
    ];

    const WEIGHT_UNIT_GRAMS = 'g';
    const WEIGHT_UNIT_KG = 'kg';
    const WEIGHT_UNIT_LB = 'lb';
    const WEIGHT_UNIT_OUNCE = 'oz';

    /** @var array $weight_units */
    public static $weight_units = [
        self::WEIGHT_UNIT_GRAMS,
        self::WEIGHT_UNIT_KG,
        self::WEIGHT_UNIT_LB,
        self::WEIGHT_UNIT_OUNCE,
    ];
}
