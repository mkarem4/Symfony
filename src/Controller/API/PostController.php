<?php
namespace App\Controller\API;
use App\Entity\Comment;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Post;
use App\Form\PostType;
use JMS\Serializer\SerializerBuilder;
use Knp\Component\Pager\PaginatorInterface;



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
     * @return Response
     */
    public function getPost(int $postId): Response
    {
        $repository = $this->getDoctrine()->getRepository(Post::class);
        $post = $repository->findById($postId);

        $serializer = SerializerBuilder::create()->build();
        $jsonObject = $serializer->serialize($post, 'json');

        // For instance, return a Response with encoded Json
        return new Response($jsonObject, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * Retrieves an post resource
     * @Rest\Get("/posts")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function getPosts(Request $request, PaginatorInterface $paginator): Response
    {
        // Retrieve the entity manager of Doctrine
        $em = $this->getDoctrine()->getManager();

        // Get some repository of data, in our case we have an Appointments entity
        $postsRepository = $em->getRepository(Post::class);

        // Find all the data on the Appointments table, filter your query as you need
        $allPostsQuery = $postsRepository->createQueryBuilder('p')
            ->getQuery();

        // Paginate the results of the query
        $posts = $paginator->paginate(
            // Doctrine Query, not results
            $allPostsQuery,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            1
        );
        $serializer = SerializerBuilder::create()->build();
        $jsonObject = $serializer->serialize($posts,'json');

        // For instance, return a Response with encoded Json
        return new Response($jsonObject, 200, ['Content-Type' => 'application/json']);
    }
        /**
     * Creates a comment resource
     * @Rest\Post("/postComment")
     * @param Request $request
     * @return Response
     */
    public function postComment(Request $request): Response
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($request->get('post_id'));
        $user = $this->getDoctrine()->getRepository(User::class)->find($request->get('user_id'));

        $comment = new Comment();
        $comment->setContent($request->get('content'));
        $comment->setPost($post);
        $comment->setUser($user);
        // Retrieve the entity manager of Doctrine
        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();
        return $this->json(['message' => 'Comment saved successfully']);
        // In case our POST was a success we need to return a 201 HTTP CREATED response
//        return new Response( 'Comment saved successfully' , 200, ['Content-Type' => 'application/json']);
    }

}