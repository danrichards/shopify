<?php

namespace Dan\Shopify\Integrations\Laravel\Http;

use Closure;
use Dan\Shopify\Util;
use Log;
use Response;

/**
 * Class WebhookMiddleware.
 */
class WebhookMiddleware
{
    /** @var \Illuminate\Http\Request */
    protected $request;

    /** @var string|null $shop */
    protected $shop = null;

    /** @var string|null $hmac */
    protected $hmac = null;

    /** @var string|null $data */
    protected $data = null;

    /** @var \stdClass|null $json */
    protected $json = null;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return JsonResponse|mixed
     */
    public function handle($request, Closure $next)
    {
        $this->request = $request;

        if (empty($this->shop = $shop = $request->headers->get('x-shopify-shop-domain'))) {
            return $this->errorWithNoShopProvided();
        }

        if (empty($this->hmac = $hmac = $request->headers->get('x-shopify-hmac-sha256'))) {
            return $this->errorWithNoHmacProvided();
        }

        if (empty($this->data = $data = file_get_contents('php://input'))) {
            return $this->errorWithNoInputData();
        }

        $this->json = $json = json_decode($data);

        if (! empty($json_error_code = json_last_error())) {
            return $this->errorWithJsonDecoding($json_error_code);
        }

        if (! Util::validWebhookHmac($hmac, $this->getSecret(), $data)) {
            return $this->errorWithHmacValidation();
        }

        return $next($request);
    }

    /**
     * @return JsonResponse
     */
    protected function errorWithNoShopProvided()
    {
        $msg = 'Header `x-shopify-shop-domain` missing.';
        $details = $this->getErrorDetails();
        Log::error($msg, $details);

        return Response::json($details, 400);
    }

    /**
     * @return JsonResponse
     */
    protected function errorWithNoHmacProvided()
    {
        $msg = 'Header `x-shopify-hmac-sha256` missing.';
        $details = $this->getErrorDetails();
        Log::error($msg, $details);

        return Response::json($details, 400);
    }

    /**
     * @return JsonResponse
     */
    protected function errorWithShopNotFound()
    {
        $url = config('services.shopify.app.app_url');
        $msg = "Shop not installed. Install at: {$url}";
        $details = $this->getErrorDetails();
        Log::error($msg, $details);

        return Response::json($details, 400);
    }

    /**
     * @return JsonResponse
     */
    protected function errorWithNoInputData()
    {
        $msg = 'No input data provided.';
        $details = $this->getErrorDetails();
        Log::error($msg, $details);

        return Response::json($details, 422);
    }

    /**
     * @param int $json_error_code
     *
     * @return JsonResponse
     */
    protected function errorWithJsonDecoding($json_error_code)
    {
        switch ($json_error_code) {
            case JSON_ERROR_DEPTH:
                $msg = 'The maximum stack depth has been exceeded';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $msg = 'Invalid or malformed JSON';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $msg = 'Control character error, possibly incorrectly encoded';
                break;
            case JSON_ERROR_SYNTAX:
                $msg = 'Syntax error';
                break;
            case JSON_ERROR_UTF8:
                $msg = 'Malformed UTF-8 characters, possibly incorrectly encoded PHP 5.3.3';
                break;
            case JSON_ERROR_RECURSION:
                $msg = 'One or more recursive references in the value to be encoded: PHP 5.5.0';
                break;
            case JSON_ERROR_INF_OR_NAN:
                $msg = 'One or more NAN or INF values in the value to be encoded: PHP 5.5.0';
                break;
            case JSON_ERROR_UNSUPPORTED_TYPE:
                $msg = 'A value of a type that cannot be encoded was given: PHP 5.5.0';
                break;
            case JSON_ERROR_INVALID_PROPERTY_NAME:
                $msg = 'A property name that cannot be encoded was given: PHP 7.0.0';
                break;
            case JSON_ERROR_UTF16:
                $msg = 'Malformed UTF-16 characters, possibly incorrectly encoded';
                break;
            default:
                $msg = 'Unknown error';
        }

        $msg = "Json error: {$msg}";
        $details = compact('json_error_code') + $this->getErrorDetails();
        Log::error($msg, $details);

        return Response::json($details, 422);
    }

    /**
     * @return JsonResponse
     */
    protected function errorWithHmacValidation()
    {
        $msg = 'Unable to verify hmac.';
        $details = compact('json_error_code') + $this->getErrorDetails();
        Log::error($msg, $details);

        return Response::json($details, 401);
    }

    /**
     * @return array
     */
    protected function getErrorDetails()
    {
        return [
            'path'    => request()->path(),
            'success' => 'false',
            'shop'    => $this->shop,
            'hmac'    => $this->hmac,
            'data'    => $this->data,
        ];
    }

    /**
     * Private apps used shared secret while installable applications use
     * application key.
     *
     * If your application uses both implementation, you may benefit from
     * overriding this method.
     *
     * @return string
     */
    protected function getSecret()
    {
        return config('shopify.webhooks.secret');
    }
}
