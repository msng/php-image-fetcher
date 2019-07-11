<?php

namespace msng\ImageFetcher;

class Image
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $content;

    /**
     * @var SafeSearchAnnotation|null
     */
    private $safeSearchAnnotation;

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return Image
     */
    public function setUrl(string $url): Image
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return Image
     */
    public function setContent(string $content): Image
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return SafeSearchAnnotation|null
     */
    public function getSafeSearchAnnotation(): ?SafeSearchAnnotation
    {
        return $this->safeSearchAnnotation;
    }

    /**
     * @param SafeSearchAnnotation $safeSearch
     * @return Image
     */
    public function setSafeSearchAnnotation(SafeSearchAnnotation $safeSearch): Image
    {
        $this->safeSearchAnnotation = $safeSearch;
        return $this;
    }
}
