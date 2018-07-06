<?php

namespace Dan\Shopify\Models;

/**
 * Class Image
 *
 * @property int $id
 * @property int $product_id
 * @property int $position
 * @property int $width
 * @property int $height
 * @property string $src
 * @property array $variant_ids
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
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
        'product_id' => 'int',
        'position' => 'int',
        'width' => 'int',
        'height' => 'int',
        'src' => 'string',
        'variant_ids' => 'array',
    ];

}
