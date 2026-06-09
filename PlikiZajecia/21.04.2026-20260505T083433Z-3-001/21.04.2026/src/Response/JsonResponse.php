<?php

namespace App\Response;

class JsonResponse extends Response
{
    public function __construct($body = '', $headers = [], $status = 200)
    {
        parent::__construct(
            json_encode($body),
            array_merge([
                'Content-Type: application/json'
            ], $headers),
            $status
        );
    }
}