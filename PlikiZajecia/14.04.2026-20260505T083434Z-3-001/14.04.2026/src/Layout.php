<?php

namespace App;

class Layout
{
    private string $page;

    private string $layoutName;

    private string $title = "INF Backend App";

    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request, string $page, string $layoutName)
    {
        $this->page = $page;
        $this->layoutName = $layoutName;
        $this->request = $request;
    }

    public function render(): void
    {
        extract([
            'title' => $this->title,
            'content' => $this->getPageContent(),
            'router' => ServiceContainer::getInstance()->get('router')
        ]);

        include __DIR__ . "/../layout/$this->layoutName.php";
    }

    public function getPageContent(): string
    {
        ob_start();
        extract([
            'request' => $this->request
        ]);
        include __DIR__ . "/../page/$this->page.php";
        return ob_get_clean();
    }
}