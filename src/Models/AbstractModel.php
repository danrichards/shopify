<?php

namespace Dan\Shopify\Models;

use ArrayAccess;
use Carbon\Carbon;
use Dan\Shopify\Util;
use DateTime;
use DateTimeInterface;
use Exception;
use JsonSerializable;
/**
 * Class AbstractModel.
 *
 * An Eloquent approach to storing and manipulating data for the Shopify Api.
 */
abstract class AbstractModel implements JsonSerializable, ArrayAccess
{
    /** @var string $resource_name */
    public static $resource_name;

    /** @var string $resource_name_many */
    public static $resource_name_many;

    /** @var string $identifier */
    public static $identifier = 'id';

    /** @var array $omit_on_replication */
    public static $omit_on_replication = ['id', 'updated_at', 'created_at'];

    /** @var array $original */
    protected $original = [];

    /** @var array $attributes */
    protected $attributes = [];

    /** @var string $date_format */
    protected $date_format = 'c';

    /** @var array $dates */
    protected $dates = [];

    /** @var array $casts */
    protected $casts = [];

    /** @var bool $exists */
    public $exists = false;

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
            $this->exists = true;
        }
    }

    /**
     * @return int|string|null
     */
    public function getKey()
    {
        return $this->original[static::$identifier] ?? null;
    }

    /**
     * Get the primary key for the model.
     *
     * @return string
     */
    public function getKeyName()
    {
        return static::$identifier;
    }

    /**
     * @param array $attributes
     *
     * @return $this
     */
    public function fill($attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }

        return $this;
    }

    /**
     * Sync the original attributes with the current.
     *
     * @param array $attributes
     *
     * @return $this
     */
    public function syncOriginal($attributes = [])
    {
        $attributes = json_decode(json_encode($attributes), true);
        $this->attributes = $attributes + $this->attributes;
        $this->original = $this->attributes;

        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Get an attribute from the model.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (! $key) {
            return;
        }

        // If the attribute exists in the attribute array or has a "get" mutator we will
        // get the attribute's value. Otherwise, we will proceed as if the developers
        // are asking for a relationship's value. This covers both types of values.
        if (array_key_exists($key, $this->attributes)
            || $this->hasGetMutator($key)) {
            return $this->getAttributeValue($key);
        }
    }

    /**
     * Get an attribute from the $attributes array.
     *
     * @param string $key
     *
     * @return mixed
     */
    protected function getAttributeFromArray($key)
    {
        if (isset($this->attributes[$key])) {
            return $this->attributes[$key];
        }
    }

    /**
     * Get the model's original attribute values.
     *
     * @param string|null $key
     * @param mixed       $default
     *
     * @return mixed|array
     */
    public function getOriginal($key = null, $default = null)
    {
        return $this->original[$key] ?? $default;
    }

    /**
     * Get the attributes that have been changed since last sync.
     *
     * @return array
     */
    public function getDirty()
    {
        $dirty = [];

        foreach ($this->attributes as $key => $value) {
            if (! array_key_exists($key, $this->original)) {
                $dirty[$key] = $value;
            } elseif ($value !== $this->original[$key] &&
                ! $this->originalIsNumericallyEquivalent($key)) {
                $dirty[$key] = $value;
            }
        }

        return $dirty;
    }

    /**
     * Determine if the new and old values for a given key are numerically equivalent.
     *
     * @param string $key
     *
     * @return bool
     */
    protected function originalIsNumericallyEquivalent($key)
    {
        $current = $this->attributes[$key];

        $original = $this->original[$key];

        // This method checks if the two values are numerically equivalent even if they
        // are different types. This is in case the two values are not the same type
        // we can do a fair comparison of the two values to know if this is dirty.
        return is_numeric($current) && is_numeric($original)
            && strcmp((string) $current, (string) $original) === 0;
    }

    /**
     * Get a plain attribute (not a relationship).
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getAttributeValue($key)
    {
        $value = $this->getAttributeFromArray($key);

        // If the attribute has a get mutator, we will call that then return what
        // it returns as the value, which is useful for transforming values on
        // retrieval from the model to a form that is more useful for usage.
        if ($this->hasGetMutator($key)) {
            return $this->mutateAttribute($key, $value);
        }

        // If the attribute exists within the cast array, we will convert it to
        // an appropriate native PHP type dependant upon the associated value
        // given with the key in the pair. Dayle made this comment line up.
        if (array_key_exists($key, $this->casts)) {
            return $this->castAttribute($key, $value);
        }

        // If the attribute is listed as a date, we will convert it to a DateTime
        // instance on retrieval, which makes it quite convenient to work with
        // date fields without having to create a mutator for each property.
        if (in_array($key, $this->dates) && ! is_null($value)) {
            return $this->asDateTime($value);
        }

        return $value;
    }

    /**
     * Determine if a get mutator exists for an attribute.
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasGetMutator($key)
    {
        return method_exists($this, 'get'.Util::studly($key).'Attribute');
    }

    /**
     * Get the value of an attribute using its mutator.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return mixed
     */
    protected function mutateAttribute($key, $value)
    {
        return $this->{'get'.Util::studly($key).'Attribute'}($value);
    }

    /**
     * Determine if a set mutator exists for an attribute.
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasSetMutator($key)
    {
        return method_exists($this, 'set'.Util::studly($key).'Attribute');
    }

    /**
     * Set a given attribute on the model.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function setAttribute($key, $value)
    {
        // First we will check for the presence of a mutator for the set operation
        // which simply lets the developers tweak the attribute as it is set on
        // the model, such as "json_encoding" an listing of data for storage.
        if ($this->hasSetMutator($key)) {
            $method = 'set'.Util::studly($key).'Attribute';

            return $this->{$method}($value);
        }

        // If an attribute is listed as a "date", we'll convert it from a DateTime
        // instance into a form proper for storage on the database tables using
        // the Shopify's date time format.
        elseif ($value && in_array($key, $this->dates)) {
            $value = $this->fromDateTime($value);
        }

        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * Set the array of model attributes. No checking is done.
     *
     * @param array $attributes
     * @param bool  $sync
     *
     * @return $this
     */
    public function setRawAttributes(array $attributes, $sync = false)
    {
        $this->attributes = $attributes;

        if ($sync) {
            $this->syncOriginal();
        }

        return $this;
    }

    /**
     * Return a timestamp as DateTime object.
     *
     * @param mixed $value
     *
     * @throws Exception
     *
     * @return Carbon
     */
    protected function asDateTime($value)
    {
        // If this value is already a Carbon instance, we shall just return it as is.
        // This prevents us having to re-instantiate a Carbon instance when we know
        // it already is one, which wouldn't be fulfilled by the DateTime check.
        if ($value instanceof Carbon) {
            return $value;
        }

        // If the value is already a DateTime instance, we will just skip the rest of
        // these checks since they will be a waste of time, and hinder performance
        // when checking the field. We will just return the DateTime right away.
        if ($value instanceof DateTimeInterface) {
            return new Carbon(
                $value->format('Y-m-d H:i:s.u'), $value->getTimezone()
            );
        }

        // If this value is an integer, we will assume it is a UNIX timestamp's value
        // and format a Carbon object from this timestamp. This allows flexibility
        // when defining your date fields as they might be UNIX timestamps here.
        if (is_numeric($value)) {
            return Carbon::createFromTimestamp($value);
        }

        // If the value is in simply year, month, day format, we will instantiate the
        // Carbon instances from that format. Again, this provides for simple date
        // fields on the database, while still supporting Carbonized conversion.
        if ($this->isStandardDateFormat($value)) {
            return Carbon::createFromFormat('Y-m-d', $value)->startOfDay();
        }

        // Finally, we will just assume this date is in the format used by default on
        // the database connection and use that format to create the Carbon object
        // that is returned back out to the developers after we convert it here.
        return Carbon::createFromFormat(
            $this->getDateFormat(), $value
        );
    }

    /**
     * Convert a DateTime to a storable string.
     *
     * @param DateTime|int $value
     *
     * @throws Exception
     *
     * @return string
     */
    public function fromDateTime($value)
    {
        return $this->asDateTime($value)->format(
            $this->getDateFormat()
        );
    }

    /**
     * Determine if the given value is a standard date format.
     *
     * @param string $value
     *
     * @return bool
     */
    protected function isStandardDateFormat($value)
    {
        return preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $value);
    }

    /**
     * Get the format for database stored dates.
     *
     * @return string
     */
    protected function getDateFormat()
    {
        return DateTime::ISO8601;
    }

    /**
     * Cast an attribute to a native PHP type.
     *
     * @param string $key
     * @param mixed $value
     *
     * @throws Exception
     *
     * @return mixed
     */
    protected function castAttribute($key, $value)
    {
        // All types to permit null
        if (is_null($value)) {
            return $value;
        }

        switch ($this->getCastType($key)) {
            case 'int':
            case 'integer':
                return (int) $value;
            case 'real':
            case 'float':
            case 'double':
                return (float) $value;
            case 'string':
                return (string) $value;
            case 'bool':
            case 'boolean':
                return (bool) $value;
            case 'datetime':
                return $this->asDateTime($value);
            case 'array':
                return (array) $value;
            case 'object':
                return (object) $value;
            default:
                return $value;
        }
    }

    /**
     * Get the type of cast for a model attribute.
     *
     * @param string $key
     *
     * @return string
     */
    protected function getCastType($key)
    {
        return isset($this->casts[$key])
            ? strtolower(trim($this->casts[$key]))
            : null;
    }

    /**
     * IMPORTANT: We only serialize what's dirty, plus the id.
     *
     * This way our api updates only receive changes.
     *
     * Be sure to call syncOriginal
     *
     * @return array
     */
    public function getPayload()
    {
        $payload = isset($this->original[static::$identifier])
            ? [static::$identifier => $this->original[static::$identifier]] + $this->getDirty()
            : $this->getDirty();

        return [static::$resource_name => $payload];
    }

    /**
     * @return string
     */
    public function jsonSerialize(): mixed
    {
        return json_encode($this->attributes + $this->original);
    }

    /**
     * @return string
     */
    public function __serialize(): array
    {
        return $this->getAttributes();
    }

    /**
     * @param string $data
     */
    public function __unserialize(array $data): void
    {
        $this->attributes = $data;
    }

    /**
     * The origin.
     *
     * @return array
     */
    public function toArray()
    {
        $arr = [];

        foreach ($this->attributes as $key => $value) {
            $arr[$key] = $this->getAttribute($key);
        }

        return $arr;
    }

    /**
     * Determine if the given attribute exists.
     *
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return isset($this->$offset);
    }

    /**
     * Get the value for a given offset.
     *
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet($offset): mixed
    {
        return $this->$offset;
    }

    /**
     * Set the value for a given offset.
     *
     * @param mixed $offset
     * @param mixed $value
     *
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        $this->$offset = $value;
    }

    /**
     * Unset the value for a given offset.
     *
     * @param mixed $offset
     *
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->$offset);
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    /**
     * Dynamically set attributes on the model.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    /**
     * Determine if an attribute or relation exists on the model.
     *
     * @param string $key
     *
     * @return bool
     */
    public function __isset($key)
    {
        return ! is_null($this->getAttribute($key));
    }

    /**
     * Unset an attribute on the model.
     *
     * @param string $key
     *
     * @return void
     */
    public function __unset($key)
    {
        unset($this->attributes[$key], $this->relations[$key]);
    }

    /**
     * Convert the model to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * @param $attribute
     * @param $prop
     * @param $value
     * @param bool $unset
     *
     * @return $this
     */
    public function prop($attribute, $prop, $value, $unset = false)
    {
        $obj = $this->$attribute ?: (object) [];

        if ($unset) {
            unset($obj->$prop);
        } else {
            $obj->$prop = $value;
        }

        $this->$attribute = $obj;

        return $this;
    }

    /**
     * Helper for pushing to an attribute that is an array.
     *
     * @param $attribute
     * @param $args
     *
     * @return int
     */
    public function push($attribute, ...$args)
    {
        $arr = (array) $this->getAttribute($attribute);

        $count = count($arr);

        foreach ($args as $arg) {
            $count = array_push($arr, $arg);
        }

        $this->setAttribute($attribute, $arr);

        return $count;
    }

    /**
     * Helper for popping from an attribute that is an array.
     *
     * @param $attribute
     *
     * @return mixed|null
     */
    public function pop($attribute)
    {
        $arr = (array) $this->getAttribute($attribute);
        $value = array_pop($arr);
        $this->setAttribute($attribute, $arr);

        return $value;
    }

    /**
     * Helper for unshifting to an attribute that is array.
     *
     * @param $attribute
     * @param $args
     *
     * @return int
     */
    public function unshift($attribute, ...$args)
    {
        $arr = (array) $this->getAttribute($attribute);

        $count = count($arr);

        foreach ($args as $arg) {
            $count = array_unshift($arr, $arg);
        }

        $this->setAttribute($attribute, $arr);

        return $count;
    }

    /**
     * Helper for shifting from an attribute that is an array.
     *
     * @param $attribute
     *
     * @return mixed|null
     */
    public function shift($attribute)
    {
        $arr = (array) $this->getAttribute($attribute);
        $value = array_shift($arr);
        $this->setAttribute($attribute, $arr);

        return $value;
    }

    /**
     * @return array
     */
    public function getCasts()
    {
        return $this->casts;
    }

    /**
     * @return static
     */
    public function replicate()
    {
        $attr = $this->getAttributes();

        $data = array_diff_key($attr, array_fill_keys(static::$omit_on_replication, null));

        return new static($data);
    }
}
