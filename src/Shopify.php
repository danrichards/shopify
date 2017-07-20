<?php

namespace Dan\Shopify;

use GuzzleHttp\Client;
use Dan\Shopify\Models\AbstractModel;
use Dan\Shopify\Models\Product;
use Dan\Shopify\Models\Order;

/**
 * Class Shopify
 */
class Shopify extends Client
{
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
        }

        return new Order($data);
    }

    /**
     * @param string|array $ids
     * @param string $append
     * @return array An array of Order models
     */
    public function findMany($ids, $append = '')
    {
        if (is_array($ids)) {
            $ids = implode(',', array_filter($ids));
        }

        return $this->all(compact('ids'), $append);
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
        $response = $this->request('POST', $endpoint, ['json' => $model]);
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
