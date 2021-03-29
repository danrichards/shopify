<?php

namespace Dan\Shopify;

use BadMethodCallException;
use Dan\Shopify\Exceptions\InvalidOrMissingEndpointException;
use Dan\Shopify\Exceptions\ModelNotFoundException;
use Dan\Shopify\Helpers\Endpoint;
use Dan\Shopify\Models\AbstractModel;
use Dan\Shopify\Models\Asset;
use Dan\Shopify\Models\Customer;
use Dan\Shopify\Models\DiscountCode;
use Dan\Shopify\Models\Dispute;
use Dan\Shopify\Models\Fulfillment;
use Dan\Shopify\Models\FulfillmentService;
use Dan\Shopify\Models\Image;
use Dan\Shopify\Models\Metafield;
use Dan\Shopify\Models\Order;
use Dan\Shopify\Models\PriceRule;
use Dan\Shopify\Models\Product;
use Dan\Shopify\Models\Risk;
use Dan\Shopify\Models\SmartCollections;
use Dan\Shopify\Models\Theme;
use Dan\Shopify\Models\Variant;
use Dan\Shopify\Models\Webhook;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use ReflectionException;

/**
 * Class Shopify.
 *
 * @property \Dan\Shopify\Helpers\Assets $assets
 * @property \Dan\Shopify\Helpers\Customers $customers
 * @property \Dan\Shopify\Helpers\DiscountCodes $discount_codes
 * @property \Dan\Shopify\Helpers\Fulfillments $fulfillments
 * @property \Dan\Shopify\Helpers\FulfillmentServices $fulfillment_services
 * @property \Dan\Shopify\Helpers\Images $images
 * @property \Dan\Shopify\Helpers\Metafields $metafields
 * @property \Dan\Shopify\Helpers\Orders $orders
 * @property \Dan\Shopify\Helpers\PriceRule $price_rules
 * @property \Dan\Shopify\Helpers\Products $products
 * @property \Dan\Shopify\Helpers\SmartCollections $smart_collections
 * @property \Dan\Shopify\Helpers\Themes $themes
 * @property \Dan\Shopify\Helpers\Risks $risks
 * @property \Dan\Shopify\Helpers\Variants $variants
 * @property \Dan\Shopify\Helpers\Webhooks $webhooks
 *
 * @method \Dan\Shopify\Helpers\Customers customers(string $customer_id)
 * @method \Dan\Shopify\Helpers\DiscountCodes discount_codes(string $discount_code_id)
 * @method \Dan\Shopify\Helpers\Fulfillments fulfillments(string $fulfillment_id)
 * @method \Dan\Shopify\Helpers\FulfillmentServices fulfillment_services(string $fulfillment_service_id)
 * @method \Dan\Shopify\Helpers\Images images(string $image_id)
 * @method \Dan\Shopify\Helpers\Metafields metafields(string $metafield_id)
 * @method \Dan\Shopify\Helpers\Orders orders(string $order_id)
 * @method \Dan\Shopify\Helpers\PriceRules price_rules(string $price_rule_id)
 * @method \Dan\Shopify\Helpers\Products products(string $product_id)
 * @method \Dan\Shopify\Helpers\Risks risks(string $risk_id)
 * @method \Dan\Shopify\Helpers\SmartCollections smart_collections(string $smart_collection_id)
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
     * The current endpoint for the API. The default endpoint is /orders/.
     *
     * @var string
     */
    public $api = 'orders';

    /**
     * The cursors for navigating current endpoint pages, if supported.
     *
     * @var array $cursors
     */
    public $cursors = [];

    /** @var array $ids */
    public $ids = [];

    /**
     * Methods / Params queued for API call.
     *
     * @var array
     */
    public $queue = [];

    /** @var string $base */
    protected $base = 'admin';

    /** @var array $last_headers */
    protected $last_headers;

    /** @var MessageInterface $last_response */
    protected $last_response;

    /** @var RateLimit $rate_limit */
    protected $rate_limit;

    /**
     * Our list of valid Shopify endpoints.
     *
     * @var array
     */
    protected static $endpoints = [
        'assets'               => 'assets.json',
        'customers'            => 'customers/%s.json',
        'discount_codes'       => 'discount_codes/%s.json',
        'disputes'             => 'shopify_payments/disputes/%s.json',
        'fulfillments'         => 'fulfillments/%s.json',
        'fulfillment_services' => 'fulfillment_services/%s.json',
        'images'               => 'images/%s.json',
        'metafields'           => 'metafields/%s.json',
        'orders'               => 'orders/%s.json',
        'price_rules'          => 'price_rules/%s.json',
        'products'             => 'products/%s.json',
        'risks'                => 'risks/%s.json',
        'smart_collections'    => 'smart_collections/%s.json',
        'themes'               => 'themes/%s.json',
        'variants'             => 'variants/%s.json',
        'webhooks'             => 'webhooks/%s.json',
    ];

    /** @var array $resource_models */
    protected static $resource_models = [
        'assets'               => Asset::class,
        'customers'            => Customer::class,
        'discount_codes'       => DiscountCode::class,
        'disputes'             => Dispute::class,
        'fulfillments'         => Fulfillment::class,
        'fulfillment_services' => FulfillmentService::class,
        'images'               => Image::class,
        'metafields'           => Metafield::class,
        'orders'               => Order::class,
        'price_rules'          => PriceRule::class,
        'products'             => Product::class,
        'risks'                => Risk::class,
        'smart_collections'    => SmartCollections::class,
        'themes'               => Theme::class,
        'variants'             => Variant::class,
        'webhooks'             => Webhook::class,
    ];

    /** @var array $cursored_enpoints */
    protected static $cursored_enpoints = [
        'customers',
        'discount_codes',
        'disputes',
        'fulfillments',
        'orders',
        'price_rules',
        'products',
        'smart_collections',
        'variants',
        'webhooks',
    ];

    /**
     * Shopify constructor.
     *
     * @param string $token
     * @param string $shop
     */
    public function __construct($shop, $token, $base = null)
    {
        $shop = Util::normalizeDomain($shop);
        $base_uri = "https://{$shop}";

        $this->setBase($base);

        parent::__construct([
            'base_uri' => $base_uri,
            'headers'  => [
                'X-Shopify-Access-Token' => $token,
                'Accept'                 => 'application/json',
                'Content-Type'           => 'application/json; charset=utf-8;',
            ],
        ]);
    }

    /**
     * @param string $shop
     * @param string $token
     *
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
     *
     * @return array
     * @throws GuzzleException
     *
     * @throws InvalidOrMissingEndpointException
     */
    public function get($query = [], $append = '')
    {
        $api = $this->api;

        // Don't allow use of page query on cursored endpoints
        if (isset($query['page']) && in_array($api, static::$cursored_enpoints, true)) {
            Util::isLaravel() && \Log::warning('vendor:dan:shopify:get', ['Use of deprecated query parameter. Use cursor navigation instead.']);


            return [];
        }

        // Do request and store response in variable
        $response = $this->request(
            $method = 'GET',
            $uri = $this->uri($append),
            $options = ['query' => $query]
        );

        // If response has Link header, parse it and set the cursors
        if ($response->hasHeader('Link')) {
            $this->cursors = static::parseLinkHeader($response->getHeader('Link')[0]);
        } 
        // If we don't have Link on a cursored endpoint then it was the only page. Set cursors to null to avoid breaking next.
        elseif (in_array($api, self::$cursored_enpoints, true)) {
            $this->cursors = [
                'prev' => null,
                'next' => null,
            ];
        }

        $data = json_decode($response->getBody()->getContents(), true);

        return $data[static::apiCollectionProperty($api)] ?? $data[static::apiEntityProperty($api)] ?? $data;
    }

    /**
     * @param array $query
     * @param string $append
     *
     * @return array|null
     *
     * @throws GuzzleException
     * @throws InvalidOrMissingEndpointException
     */
    public function next($query = [], $append = '')
    {
        // Only allow use of next on cursored endpoints
        if (! in_array($this->api, static::$cursored_enpoints, true)) {
            Util::isLaravel() && \Log::warning('vendor:dan:shopify:get', ['Use of cursored method on non-cursored endpoint.']);

            return [];
        }

        // If cursors haven't been set, then just call get normally.
        if (empty($this->cursors)) {
            return $this->get($query, $append);
        }

        // Only limit key is allowed to exist with cursor based navigation
        foreach (array_keys($query) as $key) {
            if ($key !== 'limit') {
                Util::isLaravel() && \Log::warning('vendor:dan:shopify:get', ['Limit param is not allowed with cursored queries.']);

                return [];
            }
        }

        // If cursors have been set and next hasn't been set, then return null.
        if (empty($this->cursors['next'])) {
            return [];
        }

        // If cursors have been set and next has been set, then return get with next.
        $query['page_info'] = $this->cursors['next'];

        return $this->get($query, $append);
    }

    /**
     * Get the shop resource.
     *
     * @return array
     * @throws GuzzleException
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
     *
     * @return array|AbstractModel
     * @throws GuzzleException
     *
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
     *
     * @return array|AbstractModel
     * @throws GuzzleException
     *
     * @throws InvalidOrMissingEndpointException
     */
    public function put($payload = [], $append = '')
    {
        return $this->post_or_put('PUT', $payload, $append);
    }

    /**
     * @param $post_or_post
     * @param array  $payload
     * @param string $append
     *
     * @throws InvalidOrMissingEndpointException
     * @throws GuzzleException
     *
     * @return mixed
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
     *
     * @throws GuzzleException
     * @throws InvalidOrMissingEndpointException
     *
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
     *
     * @throws GuzzleException
     * @throws InvalidOrMissingEndpointException
     * @throws ModelNotFoundException
     *
     * @return AbstractModel|null
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
     * Return an array of models or Collection (if Laravel present).
     *
     * @param string|array $ids
     *
     * @throws GuzzleException
     * @throws InvalidOrMissingEndpointException
     *
     * @return array|Collection
     */
    public function findMany($ids)
    {
        if (is_array($ids)) {
            $ids = implode(',', array_filter($ids));
        }

        return $this->all(compact('ids'));
    }

    /**
     * Shopify limits to 250 results.
     *
     * @param array $query
     * @param string $append
     *
     * @throws GuzzleException
     * @throws InvalidOrMissingEndpointException
     *
     * @return array|Collection
     */
    public function all($query = [], $append = '')
    {
        $data = $this->get($query, $append);

        if (static::$resource_models[$this->api]) {
            $class = static::$resource_models[$this->api];

            if (isset($data[$class::$resource_name_many])) {
                $data = $data[$class::$resource_name_many];
            }

            $data = array_map(static function ($arr) use ($class) {
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
     *
     * @throws GuzzleException
     * @throws InvalidOrMissingEndpointException
     *
     * @return AbstractModel
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
     *
     * @throws GuzzleException
     * @throws InvalidOrMissingEndpointException
     *
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
     *
     * @throws GuzzleException
     * @throws InvalidOrMissingEndpointException
     *
     * @return int
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
     *
     * @throws InvalidOrMissingEndpointException
     *
     * @return string
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
     *
     * @return $this
     */
    public function setBase($base = null)
    {
        if (is_null($base)) {
            $this->base = Util::isLaravel()
                ? config('shopify.api_base', 'admin')
                : 'admin';

            return $this;
        }

        $this->base = $base;

        return $this;
    }

    /**
     * @param string $api
     * @param array  $ids
     * @param array  $queue
     * @param string $append
     * @param string $base
     *
     * @throws InvalidOrMissingEndpointException
     *
     * @return string
     */
    private static function makeUri($api, $ids = [], $queue = [], $append = '', $base = 'admin')
    {
        $base = $base ?: 'admin';

        if (Util::isLaravel() && $base === 'admin') {
            $base = config('shopify.api_base', 'admin');
        }

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

        $endpoint = "/{$base}/{$endpoint}";

        if ($append) {
            $endpoint = str_replace('.json', '/'.$append.'.json', $endpoint);
        }

        return $endpoint;
    }

    /**
     * @param $payload
     *
     * @return mixed
     */
    private function normalizePayload($payload)
    {
        if ($payload instanceof AbstractModel) {
            return $payload;
        }

        if (! isset($payload['id'])) {
            if ($count = count($args = array_filter($this->ids))) {
                $last = $args[$count - 1];
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
     *
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
     *
     * @return string
     */
    private static function apiEntityProperty($api)
    {
        /** @var AbstractModel $model */
        $model = static::$resource_models[$api];

        return $model::$resource_name;
    }

    /**
     * Set our endpoint by accessing it like a property.
     *
     * @param string $endpoint
     *
     * @return $this|Endpoint
     * @throws \Exception
     */
    public function __get($endpoint)
    {
        if (array_key_exists($endpoint, static::$endpoints)) {
            $this->api = $endpoint;
        }

        $className = "Dan\Shopify\\Helpers\\".Util::studly($endpoint);

        if (class_exists($className)) {
            return new $className($this);
        }

        // If user tries to access property that doesn't exist, scold them.
        throw new \RuntimeException('Property does not exist on API');
    }

    /**
     * Set ids for one uri() call.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @throws BadMethodCallException
     *
     * @return $this
     */
    public function __call($method, $parameters)
    {
        if (array_key_exists($method, static::$endpoints)) {
            $this->ids = $parameters;

            return $this->__get($method);
        }
        $msg = sprintf('Method %s does not exist.', $method);

        throw new BadMethodCallException($msg);
    }

    /**
     * @param $responseStack
     *
     * @throws ReflectionException
     *
     * @return Helpers\Testing\ShopifyMock
     */
    public static function fake($responseStack = [])
    {
        return new Helpers\Testing\ShopifyMock($responseStack);
    }

    /**
     * Wrapper to the $client->request method.
     *
     * @param string $method
     * @param string $uri
     * @param array  $options
     *
     * @return mixed|ResponseInterface
     */
    public function request($method, $uri = '', array $options = [])
    {
        if (Util::isLaravel() && config('shopify.options.log_api_request_data')) {
            \Log::info('vendor:dan:shopify:api', compact('method', 'uri') + $options);
        }

        $this->last_response = $r = parent::request($method, $uri, $options);
        $this->last_headers = $r->getHeaders();

        if ($r->hasHeader('X-Shopify-API-Deprecated-Reason')) {
            $api_deprecated_reason = $r->getHeader('X-Shopify-API-Deprecated-Reason');
            \Log::warning('vendor:dan:shopify:api:deprecated', compact('api_deprecated_reason', 'method', 'uri') + $options + []);
        }
        $this->rate_limit = new RateLimit($r);

        return $r;
    }

    /**
     * @param callable $request
     *
     * @return array
     */
    public function rateLimited(callable $request)
    {
        try {
            return $request($this);
        } catch (ClientException $ce) {
            if ($ce->getResponse()->getStatusCode() == 429) {
                return $this->rateLimited($request);
            } else {
                throw $ce;
            }
        }
    }

    /**
     * @param bool $fetch_if_empty
     *
     * @return RateLimit
     *
     * @throws GuzzleException
     */
    public function rateLimit($fetch_if_empty = true)
    {
        if ($fetch_if_empty && empty($this->rate_limit)) {
            $this->shop();
        }

        return $this->rate_limit = $this->rate_limit
            ?: new RateLimit($this->lastResponse());
    }

    /**
     * @return array
     */
    protected function lastHeaders()
    {
        return $this->last_headers;
    }

    /**
     * @return MessageInterface
     */
    protected function lastResponse()
    {
        return $this->last_response;
    }

    /**
     * @param $linkHeader
     *
     * @return array
     */
    protected static function parseLinkHeader($linkHeader)
    {
        $cursors = [];

        foreach (explode(',', $linkHeader) as $link) {
            $data = explode(';', trim($link));
            $matches = [];
            if (preg_match('/page_info=[A-Za-z0-9]+/', $data[0], $matches)) {
                $page_info = str_replace('page_info=', '', $matches[0]);
                $rel = str_replace('"', '', str_replace('rel=', '', trim($data[1])));
                $cursors[$rel] = $page_info;
            }
        }

        return $cursors;
    }
}
