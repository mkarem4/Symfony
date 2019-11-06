<?php
namespace App\Controller\API;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
/**
 * Post controller.
 * @Route("/api", name="api_")
 */
class PostController extends FOSRestController
{

    /**
     * Retrieves an post resource
     * @Rest\Get("/posts/{postId}")
     * @param Int $postId
     * @return View
     */
    public function getPost(int $postId): View
    {
        $article = $this->PostRepository->findById($postId);
        // In case our GET was a success we need to return a 200 HTTP OK response with the request object
        return View::create($article, Response::HTTP_OK);
    }
        /**
     * Creates an Article resource
     * @Rest\Post("/posts")
     * @param Request $request
     * @return View
     */
    public function postArticle(Request $request): View
    {
        $post = new Post();
        $post->setTitle($request->get('title'));
        $post->setContent($request->get('content'));
        $this->postRepository->save($post);
        // In case our POST was a success we need to return a 201 HTTP CREATED response
        return View::create($post, Response::HTTP_CREATED);
    }

        /**
     * Lists all posts.
     * @Rest\Get("/posts")
     *
     * @return Response
     */
    public function getPostAction()
    {
        $repository = $this->getDoctrine()->getRepository(Post::class);
        $posts = $repository->findall();
        return $this->handleView($this->view($posts));
    }
//    /**
//     * Create Movie.
//     * @Rest\Post("/movie")
//     *
//     * @return Response
//     */
//    public function postMovieAction(Request $request)
//    {
//        $movie = new Movie();
//        $form = $this->createForm(MovieType::class, $movie);
//        $data = json_decode($request->getContent(), true);
//        $form->submit($data);
//        if ($form->isSubmitted() && $form->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($movie);
//            $em->flush();
//            return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
//        }
//        return $this->handleView($this->view($form->getErrors()));
//    }
}