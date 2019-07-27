<?php

namespace msng\ImageFetcher;

use Google\ApiCore\ApiException;
use Google\ApiCore\ValidationException;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Symfony\Component\DomCrawler\Crawler;

class Fetcher
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * Fetcher constructor.
     * @param HttpClient|null $httpClient
     */
    public function __construct(HttpClient $httpClient = null)
    {
        if ($httpClient) {
            $this->httpClient = $httpClient;
        } else {
            $this->httpClient = new HttpClient();
        }
    }

    /**
     * @param string $imageUrl
     * @return Image
     */
    public function fetch(string $imageUrl): Image
    {
        $imageContent = $this->httpClient->getContents($imageUrl);

        $image = (new Image())
            ->setUrl($imageUrl)
            ->setContent($imageContent);

        return $image;
    }

    /**
     * @param string $pageUrl
     * @return Image|null Returns null if no og:image is found on the web page.
     */
    public function fetchFromWebPage(string $pageUrl): ?Image
    {
        if ($imageUrl = $this->getImageUrlFromWebPage($pageUrl)) {
            return $this->fetch($imageUrl);
        }

        return null;
    }

    /**
     * @param string $image
     * @return SafeSearchAnnotation
     */
    public function safeSearch(string $image): SafeSearchAnnotation
    {
        try {
            $imageAnnotator = new ImageAnnotatorClient(['key' => '']);
            $response = $imageAnnotator->safeSearchDetection($image);
        } catch (ValidationException|ApiException $exception) {
            throw new CloudVisionException($exception->getMessage(), $exception->getCode(), $exception);
        }

        $result = $response->getSafeSearchAnnotation();

        $safeSearchAnnotation = (new SafeSearchAnnotation())
            ->setAdult($result->getAdult())
            ->setSpoof($result->getSpoof())
            ->setMedical($result->getMedical())
            ->setViolence($result->getViolence())
            ->setRacy($result->getRacy());

        return $safeSearchAnnotation;
    }

    /**
     * @param string $url
     * @return string|null
     */
    public function getImageUrlFromWebPage(string $url): ?string
    {
        $contents = $this->httpClient->getContents($url);
        $crawler = new Crawler($contents);
        $node = $crawler->filterXPath('//meta[@property="og:image"]')->first();

        if ($node->count() === 0) {
            return null;
        };

        $url = $node->attr('content');

        return $url;
    }
}
