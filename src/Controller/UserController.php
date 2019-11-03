<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bandle\FramewordExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

Class UserController extends AbstractController
{
    /**
     * @Route("/users")
     */
    public function index()
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();
        return $this->render('users/login.html.twig', array('users' => $users));
    }

    /**
     * @Route("/save", name="create_user")
     */
    public function save()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = new User();
        $user->setUsername('Mohamed Hassan');
        $user->setEmail('m.hassan@altairegypt.com');
        $user->setPassword('secret123');

        $entityManager->persist($user);
        $entityManager->flush();

        return new Response('Created new user with id: ' . $user->getId());
    }


    /**
     * @Route("/user/{id}", name="user_show")
     */
    public function show($id)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No User found for id ' . $id
            );
        }

        return new Response('Check out this great user: ' . $user->getUsername());

        // or render a template
        // in the template, print things with {{ user.name }}
        // return $this->render('user/show.html.twig', ['user' => $user]);
    }
}