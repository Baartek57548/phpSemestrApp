<?php

namespace App;

class Layout
{
    private string $page;

    private string $layoutName;

    private string $title = "INF Backend App";

    public function __construct(string $page, string $layoutName)
    {
        $this->page = $page;
        $this->layoutName = $layoutName;
    }

    public function render(): void
    {
        extract([
            'title' => $this->title,
            'content' => $this->getPageContent()
        ]);

        include __DIR__ . "/../layout/$this->layoutName.php";

    }

    public function getPageContent(): string
    {
        ob_start();
        include __DIR__ . "/../page/$this->page.php";
        return ob_get_clean();
    }
}