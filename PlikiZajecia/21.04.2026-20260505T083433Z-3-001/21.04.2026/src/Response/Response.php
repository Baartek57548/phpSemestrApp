<?php

namespace App\Response;

class Response
{
    /**
     * @var string
     */
    private $body;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var int
     */
    private $status;

    /**
     * Response constructor.
     * @param string $body
     * @param array $headers
     * @param int $status
     */
    public function __construct(string $body = '', array $headers = [], int $status = 200)
    {
        $this->body = $body;
        $this->headers = $headers;
        $this->status = $status;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return array_merge([
            sprintf('HTTP/1.1 %s', $this->status),
            sprintf('Content-Length: %d', strlen($this->body))
        ], $this->headers);
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }
}