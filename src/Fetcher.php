<?php

namespace msng\ImageFetcher;

use Exception;
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
        $response = $this->httpClient->get($imageUrl);

        $image = (new Image())
            ->setUrl($imageUrl)
            ->setContent($response->getBody()->getContents())
            ->setContentType($response->getHeaderLine('content-type'));

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
        } catch (Exception $exception) {
            throw new CloudVisionException($exception->getMessage(), $exception->getCode(), $exception);
        }

        $result = $response->getSafeSearchAnnotation();

        $safeSearchAnnotation = (new SafeSearchAnnotation());

        /**
         * In case result is null, $safeSearchAnnotation remains "Likelihood::UNKNOWN"
         */
        if ($result) {
            $safeSearchAnnotation
                ->setAdult($result->getAdult())
                ->setSpoof($result->getSpoof())
                ->setMedical($result->getMedical())
                ->setViolence($result->getViolence())
                ->setRacy($result->getRacy());
        }

        return $safeSearchAnnotation;
    }

    /**
     * @param string $url
     * @param bool $removeXmlDeclaration If true, removes XML declaration from the page in case the format is illegal and can not be parsed.
     * @return string|null
     */
    public function getImageUrlFromWebPage(string $url, bool $removeXmlDeclaration = false): ?string
    {
        $contents = $this->httpClient->getContents($url);
        $contents = trim($contents);

        if ($removeXmlDeclaration) {
            $pattern = '/^\<\?xml ([^>]+)\>/';
            $contents = preg_replace($pattern, '', $contents);
        }

        $crawler = new Crawler($contents);
        $node = $crawler->filterXPath('//meta[@property="og:image"]')->first();

        if ($node->count() === 0) {
            return null;
        };

        $url = $node->attr('content');

        return $url;
    }
}
