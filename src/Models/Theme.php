<?php

namespace Dan\Shopify\Models;

/**
 * Class Theme
 */
class Theme extends AbstractModel
{

    /** @var string $resource_name */
    public static $resource_name = 'theme';

    /** @var string $resource_name_many */
    public static $resource_name_many = 'themes';

    /** @var array $dates */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /** @var array $casts */
    protected $casts = [
        'name' => 'string',
        'role' => 'string',
        'previewable' => 'bool',
        'processing' => 'bool'
    ];

    const THEME_ROLE_MAIN = 'main';
    const THEME_ROLE_UNPUBLISHED = 'unpublished';

    /** @var array $theme_roles */
    public static $theme_roles = [
        self::THEME_ROLE_MAIN,
        self::THEME_ROLE_UNPUBLISHED
    ];
}
