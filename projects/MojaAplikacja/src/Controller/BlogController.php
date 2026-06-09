<?php

namespace App\Controller;

use App\Form\PostCommentType;
use App\Form\PostSearchType;
use App\Service\CustomPostStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class BlogController extends AbstractController
{
    public function __construct(
        private readonly CustomPostStorage $customPostStorage,
    ) {
    }

    private const POSTS = [
        [
            'slug' => 'moja-kawa',
            'title' => 'Moja kawa',
            'excerpt' => 'Dzisiaj wypiłem kawę.',
            'content' => 'Kawa była dobra. Dodałem trochę mleka i wypiłem ją przed zajęciami.'
        ],
        [
            'slug' => 'spacer',
            'title' => 'Krótki spacer',
            'excerpt' => 'Byłem na spacerze.',
            'content' => 'Pogoda była całkiem dobra. Przeszedłem kilka kilometrów i wróciłem do domu.'
        ],
        [
            'slug' => 'zakupy',
            'title' => 'Zakupy',
            'excerpt' => 'Kupiłem kilka rzeczy.',
            'content' => 'W sklepie kupiłem chleb, mleko i wodę. Nie było dużej kolejki.'
        ],
        [
            'slug' => 'nauka-php',
            'title' => 'Nauka PHP',
            'excerpt' => 'Ćwiczę PHP.',
            'content' => 'Dzisiaj nauczyłem się tworzyć tablice i wyświetlać dane na stronie.'
        ],
        [
            'slug' => 'deszczowy-dzien',
            'title' => 'Deszczowy dzień',
            'excerpt' => 'Padał deszcz.',
            'content' => 'Przez większość dnia padało. Zostałem w domu i oglądałem filmy.'
        ],
    ];

    #[Route('/', name: 'blog_index')]
    public function index(Request $request): Response
    {
        $searchForm = $this->createForm(PostSearchType::class);
        $searchForm->handleRequest($request);

        $query = trim((string) ($searchForm->get('query')->getData() ?? ''));
        $posts = $this->filterPosts($this->getAllPosts(), $query);

        return $this->render('blog/index.html.twig', [
            'posts' => $posts,
            'search_form' => $searchForm->createView(),
            'query' => $query,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/post/{slug}', name: 'blog_show')]
    public function show(string $slug, Request $request): Response
    {
        foreach ($this->getAllPosts() as $post) {
            if ($post['slug'] === $slug) {
                $commentForm = $this->createForm(PostCommentType::class);
                $commentForm->handleRequest($request);

                $storedComments = $request->getSession()->get('post_comments', []);

                if ($commentForm->isSubmitted() && $commentForm->isValid()) {
                    $data = $commentForm->getData();
                    $storedComments[$slug][] = [
                        'author' => $data['author'],
                        'email' => $data['email'],
                        'rating' => $data['rating'],
                        'message' => $data['message'],
                        'created_at' => (new \DateTimeImmutable())->format('d.m.Y H:i'),
                    ];

                    $request->getSession()->set('post_comments', $storedComments);
                    $this->addFlash('success', 'Twoja opinia została zapisana.');

                    return $this->redirectToRoute('blog_show', ['slug' => $slug]);
                }

                return $this->render('blog/show.html.twig', [
                    'post' => $post,
                    'comment_form' => $commentForm->createView(),
                    'comments' => $storedComments[$slug] ?? [],
                ]);
            }
        }

        throw $this->createNotFoundException('Nie znaleziono wpisu.');
    }

    private function getAllPosts(): array
    {
        return array_merge($this->customPostStorage->getAll(), self::POSTS);
    }

    private function filterPosts(array $posts, string $query): array
    {
        if ($query === '') {
            return $posts;
        }

        return array_values(array_filter(
            $posts,
            static function (array $post) use ($query): bool {
                $haystack = implode(' ', [
                    $post['title'],
                    $post['excerpt'],
                    $post['content'],
                ]);

                return stripos($haystack, $query) !== false;
            }
        ));
    }
}
