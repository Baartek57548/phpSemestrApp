<?php

namespace App;

class Request
{
    private array $getParams;
    private array $postParams;
    public array $urlParams;

    private function __construct($getParams, $postParams, $urlParams)
    {
        $this->getParams = $getParams;
        $this->postParams = $postParams;
        $this->urlParams = $urlParams;
    }

    public static function initializeRequest(): self
    {
        return new self($_GET, $_POST, $_SERVER);
    }
}