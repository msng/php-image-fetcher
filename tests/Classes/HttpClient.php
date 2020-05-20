<?php

namespace msng\ImageFetcher\Tests\Classes;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

class HttpClient extends \msng\ImageFetcher\HttpClient
{
    const TEST_HTML = 'https://example.com/';
    const ILLEGAL_XML = 'https://example.com/illegal-format.xml';
    const TEST_PNG = 'https://example.com/test.png';

    private $fileMap = [
        self::TEST_HTML => [
            'file' => 'test.html',
            'contentType' => 'text/html'
        ],
        self::ILLEGAL_XML => [
            'file' => 'illegal-format.xml',
            'contentType' => 'application/xml'
        ],
        self::TEST_PNG => [
            'file' => 'test.png',
            'contentType' => 'image/png'
        ]
    ];

    public function get(string $url): ResponseInterface
    {
        if (array_key_exists($url, $this->fileMap)) {
            $fileInfo = $this->fileMap[$url];
            $filename = $fileInfo['file'];
            $contentType = $fileInfo['contentType'];
            $contents = file_get_contents(__DIR__ . '/../files/' . $filename);

            return new Response(200, ['content-type' => $contentType], $contents);
        } else {
            throw new RuntimeException('Not found.', 404);
        }

    }

    /**
     * @param string $url
     * @return string
     */
    public function getContents(string $url): string
    {
        if (array_key_exists($url, $this->fileMap)) {
            $filename = $this->fileMap[$url]['file'];
            return file_get_contents(__DIR__ . '/../files/' . $filename);
        } else {
            throw new RuntimeException('Not found.', 404);
        }
    }
}

