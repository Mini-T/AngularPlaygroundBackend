<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Util\Json;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


#[Route('/api')]
class PostController extends AbstractController
{
    #[Route('/posts ', name: 'posts', methods: ['GET'])]
    public function index(PostRepository $postRepository, SerializerInterface $serializer): JsonResponse
    {

        $models = $postRepository->findAll();
        $data = $serializer->serialize($models, 'json', ['groups' => ['read']]);
        return new JsonResponse($data, Response::HTTP_OK, json: true);
    }

    #[Route('/post/new', name: 'app_post_new', methods: ['GET', 'POST'])]
    public function new(EntityManagerInterface $entityManager, Request $request, PostRepository $postRepository): JsonResponse
    {
        $data = $request -> getContent();
        $ObjectData = json_decode($data, true);
        $post = new Post();
        $title = $ObjectData['title'];
        $content = $ObjectData['content'];
        $date = $ObjectData['date'];

        $post->setTitle($title);
        $post->setContent($content);

        // 4) save the User!
        $entityManager->getRepository(Post::class);
        $entityManager->persist($post);
        $entityManager->flush();
        return new JsonResponse(null, Response::HTTP_OK);
    }

    #[Route('post/{id}', name: 'app_post_show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('post/{id}/edit', name: 'app_post_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, PostRepository $postRepository): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postRepository->add($post, true);

            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('post/{id}', name: 'app_post_delete', methods: ['POST'])]
    public function delete(Request $request, Post $post, PostRepository $postRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $postRepository->remove($post, true);
        }

        return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
    }
}
