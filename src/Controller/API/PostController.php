<?php
namespace App\Controller\API;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Post;
use App\Form\PostType;
/**
 * Post controller.
 * @Route("/api", name="api_")
 */
class PostController extends FOSRestController
{
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