<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use App\Form\UserRoleType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_index')]
    public function index(UserRepository $userRepository, PostRepository $postRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'users' => $userRepository->findBy([], ['email' => 'ASC']),
            'custom_posts' => array_map(
                static fn(Post $post): array => [
                    'slug' => $post->getSlug(),
                    'title' => $post->getTitle(),
                    'excerpt' => $post->getExcerpt(),
                ],
                $postRepository->findBy([], ['createdAt' => 'DESC'])
            ),
            'admin_count' => $userRepository->countAdmins(),
        ]);
    }

    #[Route('/admin/post/new', name: 'admin_post_new')]
    public function newPost(Request $request, EntityManagerInterface $entityManager, PostRepository $postRepository): Response
    {
        $form = $this->createForm(PostType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $slug = $this->createUniqueSlug(
                (string) $data['title'],
                array_map(
                    static fn(Post $post): string => (string) $post->getSlug(),
                    $postRepository->findAll()
                )
            );

            $currentUser = $this->getUser();

            if (!$currentUser instanceof User) {
                throw $this->createAccessDeniedException('Musisz byc zalogowany jako administrator.');
            }

            $post = new Post();
            $post->setSlug($slug);
            $post->setTitle((string) $data['title']);
            $post->setExcerpt((string) $data['excerpt']);
            $post->setContent((string) $data['content']);
            $post->setCreatedAt(new \DateTimeImmutable());
            $post->setAuthor($currentUser);

            $entityManager->persist($post);
            $entityManager->flush();
            $this->addFlash('success', 'Nowy wpis został dodany.');

            return $this->redirectToRoute('blog_show', ['slug' => $slug]);
        }

        return $this->render('admin/new_post.html.twig', [
            'post_form' => $form->createView(),
            'page_title' => 'Dodaj nowy wpis',
        ]);
    }

    #[Route('/admin/post/{slug}/edit', name: 'admin_post_edit')]
    public function editPost(string $slug, Request $request, PostRepository $postRepository, EntityManagerInterface $entityManager): Response
    {
        $post = $postRepository->findOneBy(['slug' => $slug]);

        if (!$post instanceof Post) {
            throw $this->createNotFoundException('Nie znaleziono wpisu do edycji.');
        }

        $form = $this->createForm(PostType::class, [
            'title' => $post->getTitle() ?? '',
            'excerpt' => $post->getExcerpt() ?? '',
            'content' => $post->getContent() ?? '',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $post->setTitle((string) $data['title']);
            $post->setExcerpt((string) $data['excerpt']);
            $post->setContent((string) $data['content']);
            $post->setUpdatedAt(new \DateTimeImmutable());

            $entityManager->flush();

            $this->addFlash('success', 'Wpis został zaktualizowany.');

            return $this->redirectToRoute('blog_show', ['slug' => $slug]);
        }

        return $this->render('admin/new_post.html.twig', [
            'post_form' => $form->createView(),
            'page_title' => 'Edytuj wpis',
        ]);
    }

    #[Route('/admin/post/{slug}/delete', name: 'admin_post_delete', methods: ['POST'])]
    public function deletePost(string $slug, Request $request, PostRepository $postRepository, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isCsrfTokenValid('delete_post_' . $slug, (string) $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Nieprawidłowy token CSRF.');
        }

        $post = $postRepository->findOneBy(['slug' => $slug]);

        if (!$post instanceof Post) {
            throw $this->createNotFoundException('Nie znaleziono wpisu do usunięcia.');
        }

        $entityManager->remove($post);
        $entityManager->flush();
        $this->addFlash('success', 'Wpis został usunięty.');

        return $this->redirectToRoute('admin_index');
    }

    #[Route('/admin/user/{id}/role', name: 'admin_user_role')]
    public function editUserRole(User $user, Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $currentRole = in_array('ROLE_ADMIN', $user->getRoles(), true) ? 'ROLE_ADMIN' : 'ROLE_USER';
        $currentAdmin = $this->getUser();
        $isEditingOwnLastAdminAccount = $currentAdmin instanceof User
            && $currentAdmin->getId() === $user->getId()
            && $currentRole === 'ROLE_ADMIN'
            && $userRepository->countAdmins() === 1;

        $form = $this->createForm(UserRoleType::class, [
            'role' => $currentRole,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newRole = (string) $form->get('role')->getData();

            if ($isEditingOwnLastAdminAccount && $newRole !== 'ROLE_ADMIN') {
                $this->addFlash('error', 'Nie możesz zmienić swojej roli, gdy jesteś jedynym administratorem.');

                return $this->redirectToRoute('admin_index');
            }

            $user->setRoles([$newRole]);
            $entityManager->flush();

            $this->addFlash('success', sprintf('Zmieniono rolę użytkownika %s.', $user->getEmail()));

            return $this->redirectToRoute('admin_index');
        }

        return $this->render('admin/user_role.html.twig', [
            'user_to_edit' => $user,
            'role_form' => $form->createView(),
            'role_change_blocked' => $isEditingOwnLastAdminAccount,
        ]);
    }

    #[Route('/admin/user/{id}/delete', name: 'admin_user_delete', methods: ['POST'])]
    public function deleteUser(User $user, Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        if (!$this->isCsrfTokenValid('delete_user_' . $user->getId(), (string) $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Nieprawidłowy token CSRF.');
        }

        if (in_array('ROLE_ADMIN', $user->getRoles(), true) && $userRepository->countAdmins() === 1) {
            $this->addFlash('error', 'Nie można usunąć ostatniego administratora.');

            return $this->redirectToRoute('admin_index');
        }

        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('success', sprintf('Usunięto użytkownika %s.', $user->getEmail()));

        return $this->redirectToRoute('admin_index');
    }

    private function createUniqueSlug(string $title, array $existingSlugs): string
    {
        $slug = mb_strtolower($title);
        $slug = preg_replace('/[^a-z0-9]+/iu', '-', $slug) ?? 'wpis';
        $slug = trim($slug, '-');
        $slug = $slug !== '' ? $slug : 'wpis';

        $uniqueSlug = $slug;
        $counter = 1;

        while (in_array($uniqueSlug, $existingSlugs, true)) {
            ++$counter;
            $uniqueSlug = sprintf('%s-%d', $slug, $counter);
        }

        return $uniqueSlug;
    }
}
