<?php

namespace App\Services;

use GuzzleHttp\Client;

/**
 * Class HttpClientFactory
 *
 * @author Mareks Galanskis
 */
class HttpClientFactory
{
    /**
     * Default timeout
     */
    public const HTTP_TIMEOUT = 10;

    /**
     * Create http client
     *
     * @param string $endpoint
     *
     * @return Client
     */
    public static function createClient(string $endpoint): Client
    {
        return new Client(
            [
                'base_uri' => $endpoint,
                'timeout'  => self::HTTP_TIMEOUT,
            ]
        );
    }
}
