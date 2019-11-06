<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Repository\PostRepository;
use Exception as ExceptionAlias;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    /**
     * @Route("/site", name="site")
     */
    public function index(PostRepository $postRepository)
    {
        $posts = $postRepository->findAll();
        return $this->render('site/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/post/{id}", name="post_details", methods={"GET"})
     */
    public function show(Post $post): Response
    {
        $comments = $post->getComments();
        return $this->render('site/show.html.twig', [
            'post' => $post,
            'comments' => $comments,
        ]);
    }

    /**
     * @Route("/comment/{id}", name="save_comment", methods={"POST"})
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Response
     * @throws ExceptionAlias
     */
    public function save(Request $request, $id)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);
        $comments = $post->getComments();
        $comment = new Comment();
        if ($request) {
            $comment->setContent($request->get('content'));
            $comment->setCreatedAt(new \DateTime());
            $comment->setUpdatedAt(new \DateTime());
            $comment->setUser($this->getUser());
            $comment->setPost($post);
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
            return $this->redirect($this->generateUrl('post_details', array('id' => $id)), 301);
        }

        return $this->render('site/show.html.twig', array('post' => $post, 'comments' => $comments));
    }


    /**
     * @Route("/comment/{id}", name="comment_delete_ajax", methods={"DELETE"})
     * @param Request $request
     * @param Comment $comment
     * @return RedirectResponse|Response
     */
    public function ajaxDeleteItemAction(Request $request)
    {

        if ($request->isXmlHttpRequest()) {
            $id = $request->get('id');
            $em = $this->getDoctrine()->getManager();
            $comment = $em->getRepository(Comment::class)->find($id);
            $em->remove($comment);
            $em->flush();
            $response = array(
                'status' => 'success'
            );
            return $this->json($response);
        }
        $response = array(
            'status' => 'error'
        );
        return $this->json($response);
    }
}
