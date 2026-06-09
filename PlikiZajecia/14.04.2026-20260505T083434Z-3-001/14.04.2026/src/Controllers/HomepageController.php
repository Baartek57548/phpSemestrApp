<?php

namespace App\Controllers;

use App\Request;
use App\Response;

class HomepageController implements ControllerInterface
{

    public function __invoke(Request $request): Response
    {
        // TODO: Implement __invoke() method.

        return new Response();
    }
}