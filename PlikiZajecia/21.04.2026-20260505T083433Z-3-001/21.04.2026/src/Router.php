<?php

namespace App;

use App\Controllers\ControllerInterface;

class Router
{
    private array $routes;

    public function __construct(array $availableRoutes)
    {
        $this->routes = $availableRoutes;
    }

    public function match(Request $request): string|ControllerInterface
    {
        $trimmedRequestPath = ltrim($request->getPath(), '/');
        $requestPathSegments = explode('/', $trimmedRequestPath);

        foreach ($this->routes as $routeName => $routeConfig) {
            $trimmedRoute = ltrim($routeConfig['urlPath'], '/');
            $routeSegments = explode('/', $trimmedRoute);

            $params = $this->checkRoute($routeSegments, $requestPathSegments);
            if ($params !== false) {

                $request->setParameters($params);
                return $routeConfig['controller'] ?? $routeConfig['page'];
            }
        }

        throw new \Exception('Page was not found');
    }

    public function generate($name, $params = [])
    {
        if (!isset($this->routes[$name])) {
            throw new \Exception(sprintf('Route "%s" does not exists', $name));
        }

        $path = $this->routes[$name]['urlPath'];
        $trimmedRoute = ltrim($path, '/');
        $routeSegments = explode('/', $trimmedRoute);

        $uri = [];
        for ($i = 0; $i < count($routeSegments); $i++) {
            if (preg_match('/^{(.*)}$/', $routeSegments[$i], $matches)) {
                $uri[] = $params[$matches[1]];

            } else {
                $uri[] = $routeSegments[$i];
            }
        }

        return '/' . implode('/', $uri);
    }

    /**
     * @param array $routeSegments
     * @param array $requestPathSegments
     * @return array|false
     */
    private function checkRoute(array $routeSegments, array $requestPathSegments)
    {
        $params = [];

        for ($i = 0; $i < count($routeSegments); $i++) {
            if (preg_match('/^{(.*)}$/', $routeSegments[$i], $matches)) {
                $params[$matches[1]] = $requestPathSegments[$i];
            } elseif ($routeSegments[$i] !== $requestPathSegments[$i]) {
                return false;
            }
        }

        return $params;
    }

}