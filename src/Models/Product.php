<?php

namespace Dan\Shopify\Models;

/**
 * Class Product
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

    const PUBLISEHD_SCOPE_GLOBAL = 'global';
    const PUBLISHED_SCOPE_WEB = 'web';

    /** @var array $published_scopes */
    public static $published_scopes = [
        self::PUBLISEHD_SCOPE_GLOBAL,
        self::PUBLISHED_SCOPE_WEB
    ];

}
