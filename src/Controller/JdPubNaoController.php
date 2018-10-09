<?php

namespace App\Controller;

use App\Entity\Observation;
use App\Entity\Oiseau;
use App\Form\ObservationType;
use App\Repository\OiseauRepository;
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
     * @Route("/oneArticle/{id}", name="article")
     */
    public function oneArticle(Request $request, ObjectManager $manager, Oiseau $oiseau){
        $observation = new Observation();
        $form = $this->createForm(ObservationType::class, $observation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            dump($oiseau);
            /*$manager->persist($observation);
            $manager->flush();
            return $this->redirect('blog');*/
        }
        return $this->render('lw/observation.html.twig',[
            'formObservation'=>$form->createView(),
            'oiseau'=>$oiseau
        ]);
    }

    /**
     * @Route("/allArticles", name="blog")
     */
    public function jdAllArticles(OiseauRepository $repos)
    {
        $oiseau = $repos->findAll();
        return $this->render('jd_pub_nao/Public/jdAllArticles.html.twig',[
            'oiseaux'=>$oiseau
        ]);
    }

}

