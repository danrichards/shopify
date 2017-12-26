<?php

namespace Dan\Shopify;

use GuzzleHttp\Client;
use Dan\Shopify\Models\AbstractModel;
use Dan\Shopify\Models\Product;
use Dan\Shopify\Models\Order;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class Shopify
 */
class Shopify extends Client
{

    const SCOPE_READ_ANALYTICS = 'read_analytics';
    const SCOPE_READ_CHECKOUTS = 'read_checkouts';
    const SCOPE_READ_CONTENT = 'read_content';
    const SCOPE_READ_CUSTOMERS = 'read_customers';
    const SCOPE_READ_DRAFT_ORDERS = 'read_draft_orders';
    const SCOPE_READ_FULFILLMENTS = 'read_fulfillments';
    const SCOPE_READ_ORDERS = 'read_orders';
    const SCOPE_READ_PRICE_RULES = 'read_price_rules';
    const SCOPE_READ_PRODUCTS = 'read_products';
    const SCOPE_READ_REPORTS = 'read_reports';
    const SCOPE_READ_SCRIPT_TAGS = 'read_script_tags';
    const SCOPE_READ_SHIPPING = 'read_shipping';
    const SCOPE_READ_THEMES = 'read_themes';
    const SCOPE_READ_USERS = 'read_users';
    const SCOPE_WRITE_CHECKOUTS = 'write_checkouts';
    const SCOPE_WRITE_CONTENT = 'write_content';
    const SCOPE_WRITE_CUSTOMERS = 'write_customers';
    const SCOPE_WRITE_DRAFT_ORDERS = 'write_draft_orders';
    const SCOPE_WRITE_FULFILLMENTS = 'write_fulfillments';
    const SCOPE_WRITE_ORDERS = 'write_orders';
    const SCOPE_WRITE_PRICE_RULES = 'write_price_rules';
    const SCOPE_WRITE_PRODUCTS = 'write_products';
    const SCOPE_WRITE_REPORTS = 'write_reports';
    const SCOPE_WRITE_SCRIPT_TAGS = 'write_script_tags';
    const SCOPE_WRITE_SHIPPING = 'write_shipping';
    const SCOPE_WRITE_THEMES = 'write_themes';
    const SCOPE_WRITE_USERS = 'write_users';

    /** @var array $scopes */
    public static $scopes = [
        self::SCOPE_READ_ANALYTICS,
        self::SCOPE_READ_CHECKOUTS,
        self::SCOPE_READ_CONTENT,
        self::SCOPE_READ_CUSTOMERS,
        self::SCOPE_READ_DRAFT_ORDERS,
        self::SCOPE_READ_FULFILLMENTS,
        self::SCOPE_READ_ORDERS,
        self::SCOPE_READ_PRICE_RULES,
        self::SCOPE_READ_PRODUCTS,
        self::SCOPE_READ_REPORTS,
        self::SCOPE_READ_SCRIPT_TAGS,
        self::SCOPE_READ_SHIPPING,
        self::SCOPE_READ_THEMES,
        self::SCOPE_READ_USERS,
        self::SCOPE_WRITE_CHECKOUTS,
        self::SCOPE_WRITE_CONTENT,
        self::SCOPE_WRITE_CUSTOMERS,
        self::SCOPE_WRITE_DRAFT_ORDERS,
        self::SCOPE_WRITE_FULFILLMENTS,
        self::SCOPE_WRITE_ORDERS,
        self::SCOPE_WRITE_PRICE_RULES,
        self::SCOPE_WRITE_PRODUCTS,
        self::SCOPE_WRITE_REPORTS,
        self::SCOPE_WRITE_SCRIPT_TAGS,
        self::SCOPE_WRITE_SHIPPING,
        self::SCOPE_WRITE_THEMES,
        self::SCOPE_WRITE_USERS,
    ];

    /**
     * @var string The current endpoint for the API. The default endpoint is /orders/
     */
    public $endpoint = 'orders';

    /** @var string $base */
    private static $base = 'admin';

    /**
     * @var array Our list of valid Shopify endpoints.
     */
    private static $endpoints = [
        'orders',
        'products',
    ];

    /** @var array $resource_helpers */
    private static $resource_models = [
        'orders' => Order::class,
        'products' => Product::class,
    ];

    /**
     * Shopify constructor.
     *
     * @param  string  $token
     * @param  string  $shop
     * @throws \Exception
     */
    public function __construct($shop, $token)
    {
        $base_uri = preg_replace("/(https:\/\/|http:\/\/)/", "", $shop);
        $base_uri = rtrim($base_uri, "/");
        $base_uri = str_replace('.myshopify.com', '', $base_uri);
        $base_uri = "https://{$base_uri}.myshopify.com";

        parent::__construct([
            'base_uri' => $base_uri,
            'headers'  => [
                'X-Shopify-Access-Token' => $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json; charset=utf-8;'
            ]
        ]);
    }

    /**
     * Get a resource using the assigned endpoint ($this->endpoint).
     *
     * @param  array   $payload
     * @param  string  $append
     * @return array
     */
    public function get($payload = [], $append = '')
    {
        $endpoint = static::makeEndpoint($this->endpoint, $append);

        $response = $this->request('GET', $endpoint, ['query' => $payload]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param $id
     * @return Order
     */
    public function find($id)
    {
        $data = $this->get([], $append = $id);

        if (isset(static::$resource_models[$this->endpoint])) {
            $class = static::$resource_models[$this->endpoint];

            if (isset($data[$class::$resource_name])) {
                $data = $data[$class::$resource_name];
            }

            return new $class($data);
        }

        throw new ModelNotFoundException(sprintf(
            'Model not available for `%s`', $this->endpoint));
    }

    /**
     * Return an array of models or Collection (if Laravel present)
     *
     * @param string|array $ids
     * @param string $append
     * @return array|\Illuminate\Support\Collection
     */
    public function findMany($ids, $append = '')
    {
        if (is_array($ids)) {
            $ids = implode(',', array_filter($ids));
        }

        if (isset(static::$resource_models[$this->endpoint])) {
            $class = static::$resource_models[$this->endpoint];

            $data = $this->all(compact('ids'), $append);

            if (isset($data[$class::$resource_name_many])) {
                $data = $data[$class::$resource_name_many];
            }

            $data = array_map(function($i) use ($class) {
                return new $class($i);
            }, $data);

            return defined('LARAVEL_START') ? collect($data) : $data;
        }

        throw new ModelNotFoundException(sprintf(
            'Model not available for `%s`', $this->endpoint));
    }

    /**
     * Shopify limits to 250 results
     *
     * @param array $payload
     * @param string $append
     * @return array
     */
    public function all($payload = [], $append = '')
    {
        $data = $this->get($payload, $append);

        if (static::$resource_models[$this->endpoint]) {
            $class = static::$resource_models[$this->endpoint];

            if (isset($data[$class::$resource_name_many])) {
                $data = $data[$class::$resource_name_many];
            }
        }

        return array_map(function($order) {
            return new Order($order);
        }, $data);
    }

    /**
     * Post to a resource using the assigned endpoint ($this->endpoint).
     *
     * @param  array|AbstractModel  $payload
     * @param  string  $append
     * @return array|AbstractModel
     */
    public function post($payload = [], $append = '')
    {
        $endpoint = static::makeEndpoint($this->endpoint, $append);

        $response = $this->request('POST', $endpoint, ['json' => $payload]);

        $data = json_decode($response->getBody()->getContents(), true);

        if ($payload instanceof AbstractModel) {
            if (isset($response[$payload::$resource_name])) {
                $data = $data[$payload::$resource_name];
            }

            $payload->syncOriginal($data);
        }

        return $data;
    }

    /**
     * Post to a resource using the assigned endpoint ($this->endpoint).
     *
     * @param  AbstractModel  $model
     * @param  string  $append
     * @return AbstractModel
     */
    public function save(AbstractModel $model, $append = '')
    {
        $id = $model->getAttribute('id');
        $endpoint = static::makeEndpoint($this->endpoint, $append, $id);
        $response = $this->request($id ? 'PUT' : 'POST', $endpoint, ['json' => $model]);
        $data = json_decode($response->getBody()->getContents(), true);

        if (isset($data[$model::$resource_name])) {
            $data = $data[$model::$resource_name];
        }

        $model->syncOriginal($data);

        return $model;
    }

    /**
     * Delete a resource using the assigned endpoint ($this->endpoint).
     *
     * @param  string  $id
     * @return array
     */
    public function delete($id = '')
    {
        $endpoint = static::makeEndpoint($this->endpoint, $append);
        $response = $this->request('DELETE', $endpoint);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param AbstractModel $model
     * @return array
     */
    public function destroy(AbstractModel $model)
    {
        return $this->delete($model->getAttribute('id'));
    }

    /**
     * Update a resource using the assigned endpoint ($this->endpoint).
     *
     * @param  array|AbstractModel  $payload
     * @param  string $append
     * @return array
     */
    public function put($payload = [], $append = '')
    {
        $endpoint = static::makeEndpoint($this->endpoint, $append);

        $response = $this->request('PUT', $endpoint, ['json' => $payload]);

        $data = json_decode($response->getBody()->getContents(), true);

        if ($payload instanceof AbstractModel) {
            if (isset($data[$payload::$resource_name])) {
                $data = $data[$payload::$resource_name];
            }

            $payload->syncOriginal($data);
        }

        return $data;
    }

    /**
     * @param array $payload
     * @param string $append
     * @return integer
     */
    public function count($payload = [], $append = '')
    {
        $endpoint = static::makeEndpoint($this->endpoint, $append, 'count');

        $response = $this->request('GET', $endpoint, ['query' => $payload]);

        $data = json_decode($response->getBody()->getContents(), true);

        return count($data) == 1
            ? array_values($data)[0]
            : $data;
    }

    /**
     * Set our endpoint by accessing it via a property.
     *
     * @param  string $property
     * @return $this
     */
    public function __get($property)
    {
        if (in_array($property, static::$endpoints)) {
            $this->endpoint = $property;
        }

        $className = "Dan\Shopify\\Helpers\\" . ucfirst($property);

        if (class_exists($className)) {
            return new $className($this);
        }

        return $this;
    }

    /**
     * @param $token
     * @param $shop
     * @return static
     */
    public static function make($token, $shop)
    {
        return new static($token, $shop);
    }

    /**
     * @param array ...$args
     * @return string
     */
    public static function makeEndpoint(...$args)
    {
        $args = array_merge([static::$base], $args);
        return "/".implode('/', array_filter($args)).".json";
    }
}
