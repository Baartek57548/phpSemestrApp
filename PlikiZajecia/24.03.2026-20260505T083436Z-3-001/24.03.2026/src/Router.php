<?php

namespace App;

class Router
{
    private array $routes;

    public function __construct(array $availableRoutes)
    {
        $this->routes = $availableRoutes;
    }

    public function match(Request $request): string
    {
        $urlParams = $request->urlParams;

        $page = $this->routes[$urlParams['REQUEST_URI']] ?? 'homepage';
        return $page;
    }
}