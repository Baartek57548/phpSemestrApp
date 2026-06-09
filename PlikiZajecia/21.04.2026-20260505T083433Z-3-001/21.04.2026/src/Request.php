<?php

namespace App;

class Request
{
    private array $pathParameters = [];
    private string $path;
    private array $queryParameters;

    private function __construct(
        string $path,
        array  $queryParameters)
    {
        $this->path = $path;
        $this->queryParameters = $queryParameters;
    }

    public static function prepareRequest(): self
    {
        $uri = $_SERVER['REQUEST_URI'];
        $index = strpos($uri, '?');
        if ($index === false) {
            $path = $uri;
        } else {
            $path = substr($uri, 0, $index);
        }

        return new self($path, $_GET);
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getPathParameters(): array
    {
        return $this->pathParameters;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasQueryParam(string $name)
    {
        return isset($this->queryParameters[$name]);
    }

    /**
     * @param string $name
     * @param null $default
     * @return mixed|null
     */
    public function getQueryParam(string $name, $default = null)
    {
        return $this->queryParameters[$name] ?? $default;
    }
    public function getParameter($name, $default = null)
    {
        return $this->pathParameters[$name] ?? $default;
    }
    /**
     * @param $param
     */
    public function setParameters($params)
    {
        $this->pathParameters = $params;
    }
}