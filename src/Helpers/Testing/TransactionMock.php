<?php

namespace Dan\Shopify\Helpers\Testing;

use GuzzleHttp\Psr7\Response;

/**
 * Class TransactionMock
 */
class TransactionMock
{
    /**
     * @param string $body
     * @param int $statusCode
     * @param array $headers
     * @return Response
     */
    public static function create($body = '', $statusCode = 200, $headers = [])
    {
        return new Response($statusCode, $headers, $body);
    }
}