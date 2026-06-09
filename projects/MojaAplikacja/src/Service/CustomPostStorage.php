<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

class CustomPostStorage
{
    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private readonly string $projectDir,
    ) {
    }

    public function getAll(): array
    {
        $path = $this->getPath();

        if (!is_file($path)) {
            return [];
        }

        $content = file_get_contents($path);

        if ($content === false || $content === '') {
            return [];
        }

        $posts = json_decode($content, true);

        return is_array($posts) ? $posts : [];
    }

    public function add(array $post): void
    {
        $posts = $this->getAll();
        array_unshift($posts, $post);

        $this->saveAll($posts);
    }

    public function findOneBySlug(string $slug): ?array
    {
        foreach ($this->getAll() as $post) {
            if (($post['slug'] ?? null) === $slug) {
                return $post;
            }
        }

        return null;
    }

    public function update(string $slug, array $updatedPost): void
    {
        $posts = $this->getAll();

        foreach ($posts as $index => $post) {
            if (($post['slug'] ?? null) === $slug) {
                $posts[$index] = $updatedPost;
                break;
            }
        }

        $this->saveAll($posts);
    }

    public function delete(string $slug): void
    {
        $posts = array_values(array_filter(
            $this->getAll(),
            static fn (array $post): bool => ($post['slug'] ?? null) !== $slug,
        ));

        $this->saveAll($posts);
    }

    private function saveAll(array $posts): void
    {
        $directory = dirname($this->getPath());

        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        file_put_contents($this->getPath(), json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    private function getPath(): string
    {
        return $this->projectDir.'/var/data/custom_posts.json';
    }
}
