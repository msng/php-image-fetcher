<?php

namespace msng\ImageFetcher\Tests;

use msng\ImageFetcher\Fetcher;
use msng\ImageFetcher\Tests\Classes\HttpClient;
use PHPUnit\Framework\TestCase;

class FetcherTest extends TestCase
{
    /**
     * @var Fetcher
     */
    private $fetcher;

    public function testFetchFromWebPage()
    {
        $image = $this->fetcher->fetchFromWebPage(HttpClient::TEST_HTML);

        $this->assertSame(HttpClient::TEST_PNG, $image->getUrl());
        $this->assertSame('b660148ba7416ccde4c91cef5aaced6a', md5($image->getContent()));
        $this->assertNull($image->getSafeSearchAnnotation());
    }

    public function testGetImageUrlFromWebPage()
    {
        $imageUrl = $this->fetcher->getImageUrlFromWebPage(HttpClient::TEST_HTML);
        $this->assertSame(HttpClient::TEST_PNG, $imageUrl);
    }

    public function testGetImageUrlFromIllegalXhtmlPage()
    {
        // Null is expected because the web page is illegal as XHTML.
        $imageUrl = $this->fetcher->getImageUrlFromWebPage(HttpClient::ILLEGAL_XML);
        $this->assertNull($imageUrl);

        // $removeXmlDeclaration = true
        $imageUrl = $this->fetcher->getImageUrlFromWebPage(HttpClient::ILLEGAL_XML, true);
        $this->assertSame(HttpClient::TEST_PNG, $imageUrl);
    }

    protected function setUp()
    {
        parent::setUp();
        $this->fetcher = new Fetcher(new HttpClient());
    }
}

