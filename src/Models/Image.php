<?php

namespace Dan\Shopify\Models;

/**
 * Class Image
 */
class Image extends AbstractModel
{

    /** @var string $resource_name */
    public static $resource_name = 'image';

    /** @var string $resource_name_many */
    public static $resource_name_many = 'images';

    /** @var array $dates */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /** @var array $casts */
    protected $casts = [
        'product_id' => 'string',
        'position' => 'int',
        'width' => 'int',
        'height' => 'int',
        'src' => 'int',
        'variant_ids' => 'array',
    ];

}
