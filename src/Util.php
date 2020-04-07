<?php

namespace Dan\Shopify;

use Dan\Shopify\Models\AbstractModel;
use GuzzleHttp\Client;

/**
 * Class Util.
 */
class Util
{
    /**
     * The cache of snake-cased words.
     *
     * @var array
     */
    protected static $snakeCache = [];

    /**
     * The cache of camel-cased words.
     *
     * @var array
     */
    protected static $camelCache = [];

    /**
     * The cache of studly-cased words.
     *
     * @var array
     */
    protected static $studlyCache = [];

    /**
     * Convert a string to snake case.
     *
     * @param string $value
     * @param string $delimiter
     *
     * @return string
     */
    public static function snake($value, $delimiter = '_')
    {
        $key = $value;

        if (isset(static::$snakeCache[$key][$delimiter])) {
            return static::$snakeCache[$key][$delimiter];
        }

        if (! ctype_lower($value)) {
            $value = preg_replace('/\s+/u', '', $value);

            $value = static::lower(preg_replace('/(.)(?=[A-Z])/u', '$1'.$delimiter, $value));
        }

        return static::$snakeCache[$key][$delimiter] = $value;
    }

    /**
     * Convert a value to camel case.
     *
     * @param string $value
     *
     * @return string
     */
    public static function camel($value)
    {
        if (isset(static::$camelCache[$value])) {
            return static::$camelCache[$value];
        }

        return static::$camelCache[$value] = lcfirst(static::studly($value));
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    public static function studly($value)
    {
        $key = $value;

        if (isset(static::$studlyCache[$key])) {
            return static::$studlyCache[$key];
        }

        $value = ucwords(str_replace(['-', '_'], ' ', $value));

        return static::$studlyCache[$key] = str_replace(' ', '', $value);
    }

    /**
     * Convert the given string to lower-case.
     *
     * @param string $value
     *
     * @return string
     */
    public static function lower($value)
    {
        return mb_strtolower($value, 'UTF-8');
    }

    /**
     * Flatten a multi-dimensional array into a single level.
     *
     * @param array $array
     * @param int   $depth
     *
     * @return array
     */
    public static function flatten($array, $depth = INF)
    {
        return array_reduce($array, function ($result, $item) use ($depth) {
            if (! is_array($item)) {
                return array_merge($result, [$item]);
            } elseif ($depth === 1) {
                return array_merge($result, array_values($item));
            } else {
                return array_merge($result, static::flatten($item, $depth - 1));
            }
        }, []);
    }

    /**
     * @param string $myshopify_domain
     *
     * @return string
     */
    public static function normalizeDomain($myshopify_domain)
    {
        $myshopify_domain = preg_replace("/(https:\/\/|http:\/\/)/", '', $myshopify_domain);
        $myshopify_domain = rtrim($myshopify_domain, '/');
        $myshopify_domain = strtolower($myshopify_domain);
        $myshopify_domain = str_replace('.myshopify.com', '', $myshopify_domain);

        return sprintf('%s.myshopify.com', $myshopify_domain);
    }

    /**
     * @param string $hmac
     * @param string $token
     * @param string $data
     *
     * @return bool
     */
    public static function validWebhookHmac($hmac, $token, $data)
    {
        $calculated_hmac = hash_hmac(
            $algorithm = 'sha256',
            $data,
            $token,
            $raw_output = true
        );

        return $hmac == base64_encode($calculated_hmac);
    }

    /**
     * @param $hmac
     * @param $secret
     * @param array $data
     *
     * @return bool
     */
    public static function validAppHmac($hmac, $secret, array $data)
    {
        $message = [];

        $keys = array_keys($data);
        sort($keys);
        foreach ($keys as $key) {
            $message[] = "{$key}={$data[$key]}";
        }

        $message = implode('&', $message);

        $calculated_hmac = hash_hmac(
            $algorithm = 'sha256',
            $message,
            $secret
        );

        return $hmac == $calculated_hmac;
    }

    /**
     * @param int|string|array|\stdClass|\Dan\Shopify\Models\AbstractModel $mixed
     *
     * @return int|null
     */
    public static function getKeyFromMixed($mixed)
    {
        if (is_numeric($mixed)) {
            return $mixed;
        } elseif (is_array($mixed) && isset($mixed['id'])) {
            return $mixed['id'];
        } elseif ($mixed instanceof \stdClass && isset($mixed->id)) {
            return $mixed->id;
        } elseif ($mixed instanceof AbstractModel) {
            return $mixed->getKey();
        } else {
            return;
        }
    }

    /**
     * @param string $client_id
     * @param string $client_secret
     * @param string $shop
     * @param string $code
     * @return string|false
     */
    public static function appAccessToken($client_id, $client_secret, $shop, $code, $verify = false)
    {
        $shop = static::normalizeDomain($shop);
        $base_uri = "https://{$shop}/";

        // By default, let's setup our main shopify shop.
        $config = compact('base_uri') + [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json; charset=utf-8;',
            ],
        ];

        $client = new Client($config);
        $json = compact('client_id', 'client_secret', 'code');

        $response = $client->post('admin/oauth/access_token', compact('json'));
        $body = json_decode($response->getBody(), true);

        return $body['access_token'] ?? false;
    }

    /**
     * @param $shop
     * @param $client_id
     * @param $redirect_uri
     * @param array $scopes
     * @return string
     */
    public static function appAuthUrl($shop, $client_id, $redirect_uri, $scopes = [])
    {
        $shop = static::normalizeDomain($shop);

        $url = [
            'client_id' => config('services.shopify.app.key'),
            'scope' => implode(',', (array) $scopes),
            'redirect_uri' => config('services.shopify.app.redirect'),
            'state' => md5($shop),
            'grant_options[]' => '',
            'nounce' => 'ok',
        ] + compact('client_id', 'redirect_uri');

        $url = "https://{$shop}/admin/oauth/authorize?".http_build_query($url);

        return $url;
    }

    /**
     * @param $hmac
     * @param $secret
     * @param $data
     * @return bool
     */
    public static function appValidHmac($hmac, $secret, $data)
    {
        return static::validAppHmac($hmac, $secret, $data);
    }

    /**
     * @return bool
     */
    public static function isLaravel()
    {
        return defined('LARAVEL_START') && ! static::isLumen();
    }

    /**
     * @return bool
     */
    public static function isLumen()
    {
        return function_exists('app')
            && preg_match('/lumen/i', app()->version());
    }
}
