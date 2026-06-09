<?php

namespace App;
use App\Controllers\HomepageController;
class ServiceContainer
{
    private static $instance;

    private array $services;

    private function __construct()
    {
        $this->services['router'] = new Router(
            [
                'home' => [
                    'urlPath' => '/',
                    'page' => 'homepage',
                    'controller' => new HomepageController()
                ],
                'blog' => [
                    'urlPath' => '/articles/{id}',
                    'page' => 'articles'
                ],
                'about' => [
                    'urlPath' => '/about',
                    'page' => 'about'
                ],
                'dashboard' => [
                    'urlPath' => '/dashboard',
                    'page' => 'dashboard'
                ]
            ]
        );
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function get(string $identifier)
    {
        if (!isset($this->services[$identifier])) {
            throw new \Exception("Service $identifier not found");
        }

        return $this->services[$identifier];
    }

}