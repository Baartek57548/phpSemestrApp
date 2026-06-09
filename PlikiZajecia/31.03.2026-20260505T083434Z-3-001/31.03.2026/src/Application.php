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
        $instance = ServiceContainer::getInstance();
        $request = Request::prepareRequest();
        $router = $instance->get('router');
        $this->page = $router->match($request);

        $this->layout = new Layout($this->page, 'default');
        $this->layout->render();
    }

}