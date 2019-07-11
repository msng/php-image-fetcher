<?php

namespace msng\ImageFetcher\Tests\Classes;

use RuntimeException;

class HttpClient extends \msng\ImageFetcher\HttpClient
{
    const TEST_HTML = 'https://example.com/';
    const TEST_PNG = 'https://example.com/test.png';

    private $fileMap = [
        self::TEST_HTML => 'test.html',
        self::TEST_PNG => 'test.png'
    ];

    /**
     * @param string $url
     * @return string
     */
    public function getContents(string $url): string
    {
        if (array_key_exists($url, $this->fileMap)) {
            $filename = $this->fileMap[$url];
            return file_get_contents(__DIR__ . '/../files/' . $filename);
        } else {
            throw new RuntimeException('Not found.', 404);
        }
    }
}
