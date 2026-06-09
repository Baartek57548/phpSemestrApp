<?php

namespace App\Controllers;

use App\Request;
use App\Layout;
use App\Response\Response;

/**
 * Class PageController
 */
class PageController implements ControllerInterface
{
    /**
     * @var string
     */
    private $layout;

    /**
     * @var string;
     */
    private $name;

    /**
     * PageController constructor.
     * @param string $name
     * @param string $layout
     */
    public function __construct(string $name, string $layout = 'default')
    {
        $this->name = $name;
        $this->layout = $layout;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $layout = new Layout($request, $this->name, $this->layout);
        return new Response($layout->render());
    }
}