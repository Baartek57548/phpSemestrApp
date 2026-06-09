<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Form\PostCommentType;
use App\Form\PostSearchType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class BlogController extends AbstractController
{
    public function __construct(
        private readonly PostRepository $postRepository,
        private readonly CommentRepository $commentRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

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
        $post = $this->postRepository->findOneBy(['slug' => $slug]);

        if (!$post instanceof Post) {
            throw $this->createNotFoundException('Nie znaleziono wpisu.');
        }

        $commentForm = $this->createForm(PostCommentType::class);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $data = $commentForm->getData();
            $currentUser = $this->getUser();

            if (!$currentUser instanceof User) {
                throw $this->createAccessDeniedException('Musisz byc zalogowany, aby dodac komentarz.');
            }

            $comment = new Comment();
            $comment->setContent((string) $data['message']);
            $comment->setRating((int) $data['rating']);
            $comment->setCreatedAt(new \DateTimeImmutable());
            $comment->setAuthor($currentUser);
            $comment->setPost($post);

            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            $this->addFlash('success', 'Twoj komentarz zostal zapisany.');

            return $this->redirectToRoute('blog_show', ['slug' => $slug]);
        }

        $comments = array_map(
            static fn (Comment $comment): array => [
                'author' => $comment->getAuthor()?->getEmail() ?? 'Nieznany uzytkownik',
                'rating' => $comment->getRating(),
                'message' => $comment->getContent(),
                'created_at' => $comment->getCreatedAt()?->format('d.m.Y H:i'),
            ],
            $this->commentRepository->findBy(['post' => $post], ['createdAt' => 'ASC'])
        );

        return $this->render('blog/show.html.twig', [
            'post' => [
                'slug' => $post->getSlug(),
                'title' => $post->getTitle(),
                'excerpt' => $post->getExcerpt(),
                'content' => $post->getContent(),
                'created_at' => $post->getCreatedAt()?->format('d.m.Y H:i'),
            ],
            'comment_form' => $commentForm->createView(),
            'comments' => $comments,
        ]);
    }

    private function getAllPosts(): array
    {
        return array_map(
            static fn (Post $post): array => [
                'slug' => $post->getSlug(),
                'title' => $post->getTitle(),
                'excerpt' => $post->getExcerpt(),
                'content' => $post->getContent(),
                'created_at' => $post->getCreatedAt()?->format('d.m.Y H:i'),
            ],
            $this->postRepository->findBy([], ['createdAt' => 'DESC'])
        );
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
