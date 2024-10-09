<?php

namespace msng\ImageFetcher;

class Image
{
    private string $url;

    private string $content;

    private string $contentType;

    private SafeSearchAnnotation|null $safeSearchAnnotation = null;

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): Image
    {
        $this->url = $url;
        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): Image
    {
        $this->content = $content;
        return $this;
    }

    public function getSafeSearchAnnotation(): SafeSearchAnnotation|null
    {
        return $this->safeSearchAnnotation;
    }

    public function setSafeSearchAnnotation(SafeSearchAnnotation|null $safeSearch): Image
    {
        $this->safeSearchAnnotation = $safeSearch;
        return $this;
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }

    public function setContentType(string $contentType): Image
    {
        $this->contentType = $contentType;
        return $this;
    }
}
