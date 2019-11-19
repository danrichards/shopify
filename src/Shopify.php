<?php

namespace Dan\Shopify;

use BadMethodCallException;
use Dan\Shopify\Exceptions\InvalidOrMissingEndpointException;
use Dan\Shopify\Exceptions\ModelNotFoundException;
use Dan\Shopify\Models\AbstractModel;
use Dan\Shopify\Models\Asset;
use Dan\Shopify\Models\Customer;
use Dan\Shopify\Models\Fulfillment;
use Dan\Shopify\Models\FulfillmentService;
use Dan\Shopify\Models\Image;
use Dan\Shopify\Models\Order;
use Dan\Shopify\Models\Product;
use Dan\Shopify\Models\Risk;
use Dan\Shopify\Models\Theme;
use Dan\Shopify\Models\Variant;
use Dan\Shopify\Models\Webhook;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Log;

/**
 * Class Shopify
 *
 * @property \Dan\Shopify\Helpers\Assets $assets
 * @property \Dan\Shopify\Helpers\Customers $customers
 * @property \Dan\Shopify\Helpers\Fulfillments $fulfillments
 * @property \Dan\Shopify\Helpers\FulfillmentServices $fulfillment_services
 * @property \Dan\Shopify\Helpers\Images $images
 * @property \Dan\Shopify\Helpers\Orders $orders
 * @property \Dan\Shopify\Helpers\Products $products
 * @property \Dan\Shopify\Helpers\Themes $themes
 * @property \Dan\Shopify\Helpers\Risks $risks
 * @property \Dan\Shopify\Helpers\Variants $variants
 * @property \Dan\Shopify\Helpers\Webhooks $webhooks
 * @method \Dan\Shopify\Helpers\Customers customers(string $customer_id)
 * @method \Dan\Shopify\Helpers\Fulfillments fulfillments(string $fulfillment_id)
 * @method \Dan\Shopify\Helpers\FulfillmentServices fulfillment_services(string $fulfillment_service_id)
 * @method \Dan\Shopify\Helpers\Images images(string $image_id)
 * @method \Dan\Shopify\Helpers\Orders orders(string $order_id)
 * @method \Dan\Shopify\Helpers\Products products(string $product_id)
 * @method \Dan\Shopify\Helpers\Risks risks(string $risk_id)
 * @method \Dan\Shopify\Helpers\Themes themes(string $theme_id)
 * @method \Dan\Shopify\Helpers\Variants variants(string $variant_id)
 * @method \Dan\Shopify\Helpers\Webhooks webhooks(string $webhook_id)
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
    const SCOPE_READ_ORDERS_ALL = 'read_all_orders';
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
        self::SCOPE_READ_ORDERS_ALL,
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
     * The current endpoint for the API. The default endpoint is /orders/
     *
     * @var string $api
     */
    public $api = 'orders';

    /** @var array $ids */
    public $ids = [];

    /**
     * Methods / Params queued for API call
     *
     * @var array $queue
     */
    public $queue = [];

    /** @var string $base */
    protected $base = 'admin';

    /**
     * Our list of valid Shopify endpoints.
     *
     * @var array $endpoints
     */
    protected static $endpoints = [
        'assets' => 'themes/%s/assets.json',
        'customers' => 'customers/%s.json',
        'fulfillments' => 'orders/%s/fulfillments/%s.json',
        'fulfillment_services' => 'fulfillment_services/%s.json',
        'images' => 'products/%s/images/%s.json',
        'orders' => 'orders/%s.json',
        'products' => 'products/%s.json',
        'risks' => 'orders/%s/risks/%s.json',
        'themes' => 'themes/%s.json',
        'variants' => 'products/%s/variants/%s.json',
        'webhooks' => 'webhooks/%s.json',
    ];

    /** @var array $resource_helpers */
    protected static $resource_models = [
        'assets' => Asset::class,
        'customers' => Customer::class,
        'fulfillments' => Fulfillment::class,
        'fulfillment_services' => FulfillmentService::class,
        'images' => Image::class,
        'orders' => Order::class,
        'products' => Product::class,
        'risks' => Risk::class,
        'themes' => Theme::class,
        'variants' => Variant::class,
        'webhooks' => Webhook::class,
    ];

    /**
     * Shopify constructor.
     *
     * @param string $token
     * @param string $shop
     */
    public function __construct($shop, $token, $base = null)
    {
        $base_uri = preg_replace("/(https:\/\/|http:\/\/)/", "", $shop);
        $base_uri = rtrim($base_uri, "/");
        $base_uri = str_replace('.myshopify.com', '', $base_uri);
        $base_uri = "https://{$base_uri}.myshopify.com";

        $this->setBase($base);

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
     * @param string $shop
     * @param string $token
     * @return Shopify
     */
    public static function make($shop, $token)
    {
        return new static($shop, $token);
    }

    /**
     * Get a resource using the assigned endpoint ($this->endpoint).
     *
     * @param array $query
     * @param string $append
     * @return array
     * @throws InvalidOrMissingEndpointException
     */
    public function get($query = [], $append = '')
    {
        $api = $this->api;

        $response = $this->request(
            $method = 'GET',
            $uri = $this->uri($append),
            $options = ['query' => $query]
        );

        $data = json_decode($response->getBody()->getContents(), true);

        if (isset($data[static::apiCollectionProperty($api)])) {
            return $data[static::apiCollectionProperty($api)];
        }

        if (isset($data[static::apiEntityProperty($api)])) {
            return $data[static::apiEntityProperty($api)];
        }

        return $data;
    }

    /**
     * Get the shop resource
     *
     * @return array
     */
    public function shop()
    {
        $response = $this->request('GET', "{$this->base}/shop.json");

        $data = json_decode($response->getBody()->getContents(), true);

        return $data['shop'];
    }

    /**
     * Post to a resource using the assigned endpoint ($this->api).
     *
     * @param array|AbstractModel $payload
     * @param string $append
     * @return array|AbstractModel
     * @throws InvalidOrMissingEndpointException
     */
    public function post($payload = [], $append = '')
    {
        return $this->post_or_put('POST', $payload, $append);
    }

    /**
     * Update a resource using the assigned endpoint ($this->api).
     *
     * @param array|AbstractModel $payload
     * @param string $append
     * @return array|AbstractModel
     * @throws InvalidOrMissingEndpointException
     */
    public function put($payload = [], $append = '')
    {
        return $this->post_or_put('PUT', $payload, $append);
    }

    /**
     * @param $post_or_post
     * @param array $payload
     * @param string $append
     * @return mixed
     * @throws InvalidOrMissingEndpointException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function post_or_put($post_or_post, $payload = [], $append = '')
    {
        $payload = $this->normalizePayload($payload);
        $api = $this->api;
        $uri = $this->uri($append);

        $json = $payload instanceof AbstractModel
            ? $payload->getPayload()
            : $payload;

        $response = $this->request(
            $method = $post_or_post,
            $uri,
            $options = compact('json')
        );

        $data = json_decode($response->getBody()->getContents(), true);

        if (isset($data[static::apiEntityProperty($api)])) {
            $data = $data[static::apiEntityProperty($api)];

            if ($payload instanceof AbstractModel) {
                $payload->syncOriginal($data);

                return $payload;
            }
        }

        return $data;
    }

    /**
     * Delete a resource using the assigned endpoint ($this->api).
     *
     * @param array|string $query
     * @return array
     */
    public function delete($query = [])
    {
        $response = $this->request(
            $method = 'DELETE',
            $uri = $this->uri(),
            $options = ['query' => $query]
        );

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param $id
     * @return AbstractModel|null
     * @throws ModelNotFoundException|InvalidOrMissingEndpointException
     */
    public function find($id)
    {
        try {
            $data = $this->get([], $args = $id);

            if (isset(static::$resource_models[$this->api])) {
                $class = static::$resource_models[$this->api];

                if (isset($data[$class::$resource_name])) {
                    $data = $data[$class::$resource_name];
                }

                return empty($data) ? null : new $class($data);
            }
        } catch (ClientException $ce) {
            if ($ce->getResponse()->getStatusCode() == 404) {
                $msg = sprintf('Model(%s) not found for `%s`',
                    $id, $this->api);

                throw new ModelNotFoundException($msg);
            }

            throw $ce;
        }
    }

    /**
     * Return an array of models or Collection (if Laravel present)
     *
     * @param string|array $ids
     * @return array|\Illuminate\Support\Collection
     * @throws InvalidOrMissingEndpointException
     */
    public function findMany($ids)
    {
        if (is_array($ids)) {
            $ids = implode(',', array_filter($ids));
        }

        return $this->all(compact('ids'));
    }

    /**
     * Shopify limits to 250 results
     *
     * @param array $query
     * @param string $append
     * @return array|\Illuminate\Support\Collection
     * @throws InvalidOrMissingEndpointException
     */
    public function all($query = [], $append = '')
    {
        $data = $this->get($query, $append);

        if (static::$resource_models[$this->api]) {
            $class = static::$resource_models[$this->api];

            if (isset($data[$class::$resource_name_many])) {
                $data = $data[$class::$resource_name_many];
            }

            $data = array_map(function($arr) use ($class) {
                return new $class($arr);
            }, $data);

            return defined('LARAVEL_START') ? collect($data) : $data;
        }

        return $data;
    }

    /**
     * Post to a resource using the assigned endpoint ($this->api).
     *
     * @param AbstractModel $model
     * @return AbstractModel
     * @throws InvalidOrMissingEndpointException|\GuzzleHttp\Exception\GuzzleException
     */
    public function save(AbstractModel $model)
    {
        // Filtered by uri() if falsy
        $id = $model->getAttribute($model::$identifier);

        $this->api = $model::$resource_name_many;

        $response = $this->request(
            $method = $id ? 'PUT' : 'POST',
            $uri = $this->uri(),
            $options = ['json' => $model->getPayload()]
        );

        $data = json_decode($response->getBody()->getContents(), true);

        if (isset($data[$model::$resource_name])) {
            $data = $data[$model::$resource_name];
        }

        $model->syncOriginal($data);

        return $model;
    }

    /**
     * @param AbstractModel $model
     * @return bool
     */
    public function destroy(AbstractModel $model)
    {
        $response = $this->delete($model->getOriginal($model::$identifier));

        if ($success = is_array($response) && empty($response)) {
            $model->exists = false;
        }

        return $success;
    }

    /**
     * @param array $query
     * @param string|null $id
     * @return integer
     * @throws InvalidOrMissingEndpointException
     */
    public function count($query = [])
    {
        $data = $this->get($query, 'count');

        $data = count($data) == 1
            ? array_values($data)[0]
            : $data;

        return $data;
    }

    /**
     * @param string $append
     * @return string
     * @throws InvalidOrMissingEndpointException
     */
    public function uri($append = '')
    {
        $uri = static::makeUri($this->api, $this->ids, $this->queue, $append, $this->base);

        $this->ids = [];
        $this->queue = [];

        return $uri;
    }

    /**
     * @return string
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * @param string|null $base
     * @return $this
     */
    public function setBase($base = null)
    {
        if (is_null($base)) {
            $this->base = defined('LARAVEL_START')
                ? config('shopify.api_base', 'admin')
                : $this->base = 'admin';

            return $this;
        }

        $this->base = $base;

        return $this;
    }

    /**
     * @param string $api
     * @param array $ids
     * @param array $queue
     * @param string $append
     * @return string
     * @throws InvalidOrMissingEndpointException
     */
    private static function makeUri($api, $ids = [], $queue = [], $append = '', $base = 'admin')
    {
        // Is it an entity endpoint?
        if (substr_count(static::$endpoints[$api], '%') == count($ids)) {
            $endpoint = vsprintf(static::$endpoints[$api], $ids);

        // Is it a collection endpoint?
        } elseif (substr_count(static::$endpoints[$api], '%') == (count($ids) + 1)) {
            $endpoint = vsprintf(str_replace('/%s.json', '.json', static::$endpoints[$api]), $ids);

        // Is it just plain wrong?
        } else {
            $msg = sprintf('You did not specify enough ids for endpoint `%s`, ids(%s).',
                static::$endpoints[$api],
                implode($ids));

            throw new InvalidOrMissingEndpointException($msg);
        }

        // Prepend parent APIs until none left.
        while ($parent = array_shift($queue)) {
            $endpoint = implode('/', array_filter($parent)).'/'.$endpoint;
        }

        $endpoint = '/'.$base.'/'.$endpoint;

        if ($append) {
            $endpoint = str_replace('.json', '/'.$append.'.json', $endpoint);
        }

        return $endpoint;
    }

    /**
     * @param $payload
     * @return mixed
     */
    private function normalizePayload($payload)
    {
        if ($payload instanceof AbstractModel) {
            return $payload;
        }

        if (! isset($payload['id'])) {
            if ($count = count($args = array_filter($this->ids))) {
                $last = $args[$count-1];
                if (is_numeric($last)) {
                    $payload['id'] = $last;
                }
            }
        }

        $entity = $this->getApiEntityProperty();

        return [$entity => $payload];
    }

    /**
     * @return string
     */
    private function getApiCollectionProperty()
    {
        return static::apiCollectionProperty($this->api);
    }

    /**
     * @param string $api
     * @return string
     */
    private static function apiCollectionProperty($api)
    {
        /** @var AbstractModel $model */
        $model = static::$resource_models[$api];
        return $model::$resource_name_many;
    }

    /**
     * @return string
     */
    private function getApiEntityProperty()
    {
        return static::apiEntityProperty($this->api);
    }

    /**
     * @param string $api
     * @return string
     */
    private function apiEntityProperty($api)
    {
        /** @var AbstractModel $model */
        $model = static::$resource_models[$api];
        return $model::$resource_name;
    }

    /**
     * Set our endpoint by accessing it like a property.
     *
     * @param string $endpoint
     * @return $this
     */
    public function __get($endpoint)
    {
        if (array_key_exists($endpoint, static::$endpoints)) {
            $this->api = $endpoint;
        }

        $className = "Dan\Shopify\\Helpers\\" . Util::studly($endpoint);

        if (class_exists($className)) {
            return new $className($this);
        }

        return $this;
    }

    /**
     * Set ids for one uri() call.
     *
     * @param string $method
     * @param array $parameters
     * @return $this
     * @throws BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        if (array_key_exists($method, static::$endpoints)) {
            $this->ids = array_merge($this->ids, $parameters);
            return $this->__get($method);
        }
        $msg = sprintf('Method %s does not exist.', $method);

        throw new BadMethodCallException($msg);
    }

    /**
     * @param $responseStack
     * @return Helpers\Testing\ShopifyMock
     * @throws \ReflectionException
     */
    public static function fake($responseStack = [])
    {
        return new Helpers\Testing\ShopifyMock($responseStack);
    }

    /**
     * Wrapper to the $client->request method
     *
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request($method, $uri = '', array $options = [])
    {
        if (env('SHOPIFY_OPTION_LOG_API_REQUEST') || config('shopify.options.log_api_request_data')){
            Log::info('SHOPIFY API Request', compact('method', 'uri') + $options);
        }
        return parent::request($method, $uri, $options);
    }
}
