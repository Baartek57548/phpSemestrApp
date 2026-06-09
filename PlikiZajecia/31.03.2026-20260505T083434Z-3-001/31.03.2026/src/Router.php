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
        $page = $this->routes[$request->getPath()] ?? 'homepage';
        return $page;
    }
}