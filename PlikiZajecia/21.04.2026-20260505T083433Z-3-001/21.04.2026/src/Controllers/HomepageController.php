<?php

namespace App\Controllers;

use App\Request;
use App\Response\Response;
use App\Response\JsonResponse;

class HomepageController implements ControllerInterface
{
    public function __invoke(Request $request): Response
    {
        return new JsonResponse([
            'Homepage Test Response',
            'param1' => 123
        ]);
    }
}