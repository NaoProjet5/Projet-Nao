<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class JdPubNaoController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function jdHome()
    {
        return $this->render('jd_pub_nao/Public/jdHome.html.twig');
    }

    /**
     * @Route("/about", name="aboutUs")
     */
    public function jdAboutUs()
    {
        return $this->render('jd_pub_nao/Public/jdAbout.html.twig');
    }

    /**
     * @Route("/mapBirds", name="birds")
     */
    public function jdMapBirds()
    {
        return $this->render('jd_pub_nao/Public/jdMapBirds.html.twig');
    }

    /**
     * @Route("/allArticles", name="blog")
     */
    public function jdAllArticles()
    {
        return $this->render('jd_pub_nao/Public/jdAllArticles.html.twig');
    }
}

