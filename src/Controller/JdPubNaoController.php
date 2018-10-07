<?php

namespace App\Controller;

use App\Entity\Observation;
use App\Form\ObservationType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/oneArticle", name="article")
     */
    public function oneArticle(Request $request, ObjectManager $manager){
        $observation = new Observation();
        $form = $this->createForm(ObservationType::class, $observation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($observation);
            $manager->flush();
            return $this->redirect('blog');
        }
        return $this->render('lw/observation.thml.twig',[
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/allArticles", name="blog")
     */
    public function jdAllArticles()
    {
        return $this->render('jd_pub_nao/Public/jdAllArticles.html.twig');
    }

}

