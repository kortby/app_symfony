<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Form\PostsType;
use App\Repository\PostsRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/posts")
 */
class PostsController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {

        $this->security = $security;
    }
    /**
     * @Route("/", name="posts_index", methods={"GET"})
     */
    public function index(PostsRepository $postsRepository): Response
    {

        //$this->denyAccessUnlessGranted('POST_VIEW', $postsRepository);
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('posts/index.html.twig', [
            'posts' => $postsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="posts_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $post = new Posts();
        $post->setUser($this->security->getUser());
        $form = $this->createForm(PostsType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('posts_index');
        }

        return $this->render('posts/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="posts_show", methods={"GET"})
     */
    public function show(Posts $post): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // $this->denyAccessUnlessGranted('POST_VIEW', $post);

        return $this->render('posts/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="posts_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Posts $post): Response
    {
        try {

            $this->denyAccessUnlessGranted('POST_EDIT', $post);
            $form = $this->createForm(PostsType::class, $post);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('posts_index');
            }

            return $this->render('posts/edit.html.twig', [
                'post' => $post,
                'form' => $form->createView(),
            ]);
        } catch (Exception $e) {
        }
        $this->addFlash('notice', 'You can not Edit Post that is not yours');
        return $this->redirectToRoute('posts_index');
    }

    /**
     * @Route("/{id}", name="posts_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Posts $post): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('posts_index');
    }
}
