<?php

namespace Dan\Shopify\Models;

/**
 * Class Metafields
 * @package Dan\Shopify\Models
 *
 * @property int $id
 * @property string $description
 * @property string $key
 * @property string $namespace
 * @property int $owner_id
 * @property string $owner_resource
 * @property mixed $value
 * @property string $value_type
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 *
 */
class Metafield extends AbstractModel
{
    /** @var string $resource_name */
    public static $resource_name = 'metafield';

    /** @var string $resource_name_many */
    public static $resource_name_many = 'metafields';

    /** @var array $dates */
    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
