<?php

namespace Dan\Shopify\Models;

/**
 * Class Asset
 */
class Asset extends AbstractModel
{

    /** @var array $omit_on_replication */
    public static $omit_on_replication = [
        'id',
        'public_url',
        'content_type',
        'size',
        'theme_id',
        'warnings',
        'created_at',
        'updated_at',
    ];

    /** @var string $identifier */
    public static $identifier = 'key';

    /** @var string $resource_name */
    public static $resource_name = 'asset';

    /** @var string $resource_name_many */
    public static $resource_name_many = 'assets';

    /** @var array $dates */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /** @var array $casts */
    protected $casts = [
        'key' => 'string',
        'public_url' => 'string',
        'value' => 'string',
        'content_type' => 'string',
        'size' => 'integer',
        'theme_id' => 'integer',
    ];

    /**
     * AbstractModel constructor.
     *
     * @param array|object $data
     */
    public function __construct($data = [])
    {
        $data = json_decode(json_encode($data), true);

        $this->fill($data);

        // Unlike Laravel, we sync the original after filling
        if (isset($data[static::$identifier])) {
            $this->syncOriginal();
            // No ID! We can't be sure it exists because we only PUT with `key`
            $this->exists = false;
        }
    }

    /**
     * It'll be groovy if we append `_copy` before the extension.
     *
     * @return static
     */
    public function replicate()
    {
        $attr = $this->getAttributes();

        $dot = strrpos($attr['key'], '.') ?: strlen($attr['key']);
        $key = substr($attr['key'], 0, $dot)
            .'.copy'.substr($attr['key'], $dot);

        $data = compact('key');
        $data += array_diff_key($attr, array_fill_keys(static::$omit_on_replication, null));

        return new static($data);
    }

}
