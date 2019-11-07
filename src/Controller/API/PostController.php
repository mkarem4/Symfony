<?php
namespace App\Controller\API;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Entity\Post;
use JMS\Serializer\SerializerBuilder;



/**
 * Post controller.
 */
class PostController extends AbstractFOSRestController
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
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function getPosts(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Post::class);
        $posts = $repository->findAll();
        $serializer = SerializerBuilder::create()->build();
        $jsonObject = $serializer->serialize($posts,'json');

        // For instance, return a Response with encoded Json
        return new Response($jsonObject, 200, ['Content-Type' => 'application/json']);
    }

}