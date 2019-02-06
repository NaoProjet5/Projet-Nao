<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\JdUsers;
use App\Entity\LwArticle;
use App\Entity\Observation;
use App\Entity\Oiseau;
use App\Form\CommentType;
use App\Form\ObservationType;
use App\LwServices\FileUploader;
use App\Repository\CommentRepository;
use App\Repository\LwArticleRepository;
use App\Repository\ObservationRepository;
use App\Repository\OiseauRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Elasticsearch\Client;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Form\LwArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use diversen\meta;
use Elasticsearch\ClientBuilder;


class JdPubNaoController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function jdHome(LwArticleRepository $artiRepos, ObservationRepository $obserRepos)
    {
        $article = $artiRepos->getFiveArticle();
        $observation = $obserRepos->getFiveObservation();
        return $this->render('jd_pub_nao/Public/jdHome.html.twig',[
            'articles'=>$article,
            'observations'=>$observation,
        ]);
    }

    /**
     * @Route("/presentation", name="aboutUs")
     */
    public function jdAboutUs(OiseauRepository $repos)
    {
        /*https://afsy.fr/avent/2017/20-elasticsearch-6-et-symfony-4
        $data = $repos->findLimitBird(1);
        $client = ClientBuilder::create()->build();
        $params['body'][] = [
            'nomValide' => 'my_value'
        ];

        $response = $client->index($params);
        dump($response);
        die();*/
        return $this->render('jd_pub_nao/Public/jdAbout.html.twig');
    }

    /**
     * @Route("/les_oiseaux", name="birds")
     */
    public function jdAllBirds(OiseauRepository $repos, Request $request)
    {
        /*Pour limiter les données pour les soucis de traitement c'est mieux cette fonction*/
        $oiseau = $repos->findLimitBird(20);
        /*Pour revenir à la normal c'est mieux cette fonction*/
        //$oiseau = $repos->findAll();
        /* boucle pour la creation des urls des images des oiseaux*/
        dump($oiseau);
        die();
        foreach ($oiseau as $data) {
            if ( preg_match('/http/',$data->getUrl()) == true ){
                $url = get_meta_tags($data->getUrl(),'https://inpn.mnhn.fr/photos/uploads');
                $data->setUrl($url['twitter:image']);
            }

        }
        $bird_name = $repos->name_bird();
        /* @var $paginator \Knp\Component\Pager\Paginator */
        $paginator  = $this->get('knp_paginator');
        // Paginate the results of the query
        $appointments = $paginator->paginate(
        // Doctrine Query, not results
            $oiseau,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            8
        );
        return $this->render('jd_pub_nao/Public/jdMapBirds.html.twig',[
            'oiseaux'=>$appointments,'bird_name'=>$bird_name
        ]);
    }
    /**
     * @Route("/observations", name="observations")
     */
    public function jdAllObs(ObservationRepository $repos)
    {
        $observations = $repos->getObs();

        return $this->render('jd_pub_nao/Public/jdObservation.html.twig',['observation'=>$observations
        ]);
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
    /**
     * @Route("/liste-oiseaux/{id}", name="bird")
     * @Route("/faire-une-observation", name="faire-une-observation")
     */
    public function oneBird( Request $request, ObjectManager $manager, Oiseau $oiseau = NULL, FileUploader $fileUploader, Security $security, ObservationRepository $repos_obs,OiseauRepository $repos_bird){
        $observation = new Observation();
        $data = $repos_obs->findBy(['valide'=>1,'oiseau'=>$oiseau]);
        $bird_name = $repos_bird->name_bird();
        $form = $this->createForm(ObservationType::class, $observation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            //$observation->setCreatedAt(new \DateTime());
            $user = $security->getUser();
            $observation->setUser($user);

            $file = $observation->getPhoto();
            if ($file <> null){
                $fileName = $fileUploader->upload($file);
                $observation->setPhoto($fileName);
            }
            $observation->setValide(0);
            $observation->setOiseau($oiseau);
            $manager->persist($observation);
            $manager->flush();
            $this->addFlash('notice_obs','Merci pour votre observation pour rendre publique nos spécialistes vont étudier pour une validation !!!');
            return $this->redirectToRoute('bird',['id'=>$oiseau->getId()]);
        }
        return $this->render('lw_pub_nao/lwObservation.html.twig',[
            'formObservation'=>$form->createView(),
            'oiseau'=>$oiseau,
            'observations'=>$data,
            'bird_name'=>$bird_name
        ]);
    }

    /**
     * @Route("/blog", name="blog")
     */
    public function jdAllArticles(LwArticleRepository $repos,Request $request)
    {
        $article = $repos->findBy(['alive'=>1]);
        /* @var $paginator \Knp\Component\Pager\Paginator */
        $paginator  = $this->get('knp_paginator');
        // Paginate the results of the query
        $appointments = $paginator->paginate(
        // Doctrine Query, not results
            $article,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            8
        );
        return $this->render('jd_pub_nao/Public/jdAllArticles.html.twig',[
            'articles'=>$appointments
        ]);
    }


    /**
     * @Route("/comment/{id}", name="signalComment")
     */
    public function signalComment( ObjectManager $manager, $id, CommentRepository $repos){
        $comment = $repos->find($id);
        if ($comment->getSignale() == 0 || $comment->getSignale() == Null){
            $comment->setSignale(1);
        }
        $manager->flush();
        return $this->redirectToRoute('oneArticle',['id'=>$comment->getArticle()->getId()]);
    }




}

