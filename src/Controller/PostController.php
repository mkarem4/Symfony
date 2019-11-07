<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Service\ImageUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/admin/post")
 */
class PostController extends AbstractController
{
    /**
     * return list of posts
     * @Route("/", name="post_index", methods={"GET"})
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
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

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/new", name="post_new", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $fileUploader, ImageUploader $imageUploader): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form['file']->getData();
            if ($file) {
                $fileName = $fileUploader->upload($file);
                $post->setFile($fileName);
            }

            /** @var UploadedFile $image */
            $img = $form['featured_image']->getData();
            if ($img) {
                $fileName = $imageUploader->upload($img);
                $post->setFeaturedImage($fileName);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('post_index');
        }

        return $this->render('post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_show", methods={"GET"})
     */
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="post_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Post $post, FileUploader $fileUploader, ImageUploader $imageUploader): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $file */
            $file = $form['file']->getData();
            if ($file) {
                $fileName = $fileUploader->upload($file);
                if($fileName)
                {
                    $filePath =  $this->getParameter('uploads_directory');
                    //remove old file if exists
                    $post->removeUploadedFile($filePath);
                    $post->setFile($fileName);
                }

            }

            /** @var UploadedFile $image */
            $img = $form['featured_image']->getData();
            if ($img) {
                $imgName = $imageUploader->upload($img);
                if($imgName)
                {
                    $path =  $this->getParameter('featured_images_directory');
                    $thumbPath =  $this->getParameter('featured_images_thumb_directory');
                    //remove old file if exists
                    $post->removeUploadedImages($path, $thumbPath);

                    $post->setFeaturedImage($imgName);
                }
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('post_index');
        }

        return $this->render('post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Post $post): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $path = $this->getParameter('featured_images_directory');
            $thumbPath = $this->getParameter('featured_images_thumb_directory');
            $filePath = $this->getParameter('uploads_directory');
            $post->removeUploadedImages($path, $thumbPath);
            $post->removeUploadedFile($filePath);
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('post_index');
    }
}
