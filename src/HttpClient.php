<?php

namespace msng\ImageFetcher;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class HttpClient
{
    const GET = 'get';

    /**
     * @var GuzzleClientInterface
     */
    private $guzzleClient;

    /**
     * @param array $guzzleConfig
     */
    public function __construct(array $guzzleConfig = [])
    {
        $this->guzzleClient = new GuzzleClient($guzzleConfig);
    }

    /**
     * @param string $url
     * @return ResponseInterface
     */
    public function get(string $url): ResponseInterface
    {
        try {
            return $this->guzzleClient->request(self::GET, $url);
        } catch (GuzzleException $exception) {
            throw new ImageFetcherException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * @param string $url
     * @return string
     */
    public function getContents(string $url): string
    {
        $image = $this->get($url);

        return $image->getBody()->getContents();
    }
}
