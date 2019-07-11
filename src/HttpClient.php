<?php

namespace msng\ImageFetcher;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\Exception\GuzzleException;

class HttpClient
{
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
     * @return string
     */
    public function getContents(string $url): string
    {
        try {
            $image = $this->guzzleClient->request('GET', $url);
        } catch (GuzzleException $exception) {
            throw new ImageFetcherException($exception->getMessage(), $exception->getCode(), $exception);
        }

        $contents = $image->getBody()->getContents();

        return $contents;
    }
}
