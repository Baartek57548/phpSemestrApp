<?php

namespace App\Controllers;

use App\Request;
use App\Response;


interface ControllerInterface
{
    public function __invoke(Request $request): Response;
}