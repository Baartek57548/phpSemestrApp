<?php

namespace App;

class Application
{
    /**
     * @var string
     */
    private string $page;

    /**
     * @var Layout
     */
    private Layout $layout;

    public function run(): void
    {
        $request = Request::initializeRequest();
        $router = new Router($this->getRoutes());
        $this->page = $router->match($request);

        $this->layout = new Layout($this->page, 'default');
        $this->layout->render();
    }
    public function getRoutes():array
    {
        return [
            '/' => 'homepage',
            '/about' => 'about',
            '/articles' => 'articles',
            '/dashboard' => 'dashboard',
        ];
    }
}