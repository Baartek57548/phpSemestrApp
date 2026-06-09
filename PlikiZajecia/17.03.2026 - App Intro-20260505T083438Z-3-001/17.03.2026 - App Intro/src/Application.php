<?php

namespace App;

class Application
{
    /**
     * @var array|string[]
     */
    private array $allowedPages = [
        'homepage',
        'about',
        'articles',
        'dashboard'
    ];

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
        $this->parseRouting();
        $this->layout = new Layout($this->page, 'default');
        $this->layout->render();
    }

    private function parseRouting(): void
    {
        $this->page = $_GET['page'] ?? 'homepage';
        if (!in_array($this->page, $this->allowedPages)) {
            $this->page = 'homepage';
        }
    }
}