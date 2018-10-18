<?php

namespace App\Controller;

use App\Entity\LwArticle;
use App\Form\LwArticleType;
use App\Repository\LwArticleRepository;
use App\Repository\ObservationRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Geocoder\Query\GeocodeQuery;
use Geocoder\Query\ReverseQuery;

class LwController extends AbstractController
{
    /**
     * @Route("/lw", name="lw")
     */
    public function index()
    {
        $httpClient = new \Http\Adapter\Guzzle6\Client();
        $provider = new \Geocoder\Provider\GoogleMaps\GoogleMaps($httpClient);
        $geocoder = new \Geocoder\StatefulGeocoder($provider, 'fr');
        dump($provider);
        die();
        return $this->render('lw/index.html.twig', [
            'controller_name' => 'LwController',
        ]);
    }

    /**
     * @Route ("/lw/new_article", name="new_article")
     */
    public function create(Request $request,ObjectManager $manager) {

        $article = new LwArticle();
        $form = $this->createForm(LwArticleType::class, $article);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $article->setCreatedAt(new \DateTime());
            $article->setAlive(1);
            $manager->persist($article);
            $manager->flush();
            return $this->redirectToRoute('blog_show',['id'=>$article->getId()]);
        }
        return $this->render('lw/create.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route ("/lw/article/{id}",name="blog_show")
     */
    public function show_article(LwArticle $article){
        return $this->render('lw/OneArticle.html.twig',[
            'article'=>$article
        ]);

    }

    /**
     * @route ("/lw/AdminObservation", name="admin_observation")
     */
    public function adminObservation(ObservationRepository $repos){
        $observation = $repos->findAll();
        return $this->render('lw/adminObservation.html.twig',[
            'observations'=>$observation
        ]);

    }
    /**
     * @route ("/lw/AdminArticle", name="admin_article")
     */
    public function adminArticle( LwArticleRepository $repos){
        $article = $repos->findAll();
        return $this->render('lw/adminArticle.html.twig',[
            'articles' => $article
        ]);

    }
}
