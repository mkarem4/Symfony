<?php

namespace App\Controller;

use Sensio\Bandle\FramewordExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

Class ArticleController extends AbstractController
{
    /*
     * @ROUTE("/home")
     * @METHOD({"GET"})
     */
    public function index()
    {
        $number = random_int(0, 100);
        return $this->render('articles/index.html.twig', array('number' => $number));
    }
}