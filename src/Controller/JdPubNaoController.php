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
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Form\LwArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


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
     * @Route("/about", name="aboutUs")
     */
    public function jdAboutUs()
    {
        return $this->render('jd_pub_nao/Public/jdAbout.html.twig');
    }

    /**
     * @Route("/mapBirds", name="birds")
     */
    public function jdAllBirds(OiseauRepository $repos, Request $request)
    {
        $oiseau = $repos->findAll();
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
            'oiseaux'=>$appointments
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
     * @Route("/oneBird/{id}", name="bird")
     */
    public function oneBird(Request $request, ObjectManager $manager, Oiseau $oiseau, FileUploader $fileUploader, Security $security){
        $observation = new Observation();
        $form = $this->createForm(ObservationType::class, $observation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $observation->setCreatedAt(new \DateTime());
            $user = $security->getUser();
            $observation->setUser($user);
            $file = $observation->getPhoto();
            $fileName = $fileUploader->upload($file);
            $observation->setPhoto($fileName);
            $observation->setValide(0);
            $observation->setOiseau($oiseau);
            $manager->persist($observation);
            $manager->flush();
            $this->addFlash('notice','Merci pour votre observation nous allons vérifier pour valider !!!');
            return $this->redirectToRoute('bird',['id'=>$oiseau->getId()]);
        }
        return $this->render('lw_pub_nao/lwObservation.html.twig',[
            'formObservation'=>$form->createView(),
            'oiseau'=>$oiseau
        ]);
    }

    /**
     * @Route("/allArticles", name="blog")
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
     * @Route("/article/{id}", name="oneArticle")
     */
    public function lwOneArticle(Request $request, ObjectManager $manager, LwArticle $article, Security $security){
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $comment->setCreatedAt(new \DateTime());
            $user = $security->getUser();
            $comment->setAuthor($user);
            $comment->setSignale(0);
            $comment->setArticle($article);
            $manager->persist($comment);
            $manager->flush();
            return $this->redirectToRoute('oneArticle',['id'=>$article->getId()]);

        }

        return $this->render('jd_pub_nao/Public/lwArticle.html.twig',[
            'article'=>$article,
            'formComment'=>$form->createView()
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

    /**
     * @Route ("/lw/new_article", name="new_article")
     */
    public function create(Request $request,ObjectManager $manager, FileUploader $fileUploader) {

        $article = new LwArticle();
        $form = $this->createForm(LwArticleType::class, $article);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $article->setCreatedAt(new \DateTime());
            $article->setAlive(1);
            $article->setUsers($this->getUser());
            $file = $form->get('photo')->getData();
            $fileName = $fileUploader->upload($file);
            $article->setPhoto($fileName);
            $manager->persist($article);
            $manager->flush();
            return $this->redirectToRoute('blog_show',['id'=>$article->getId()]);
        }
        return $this->render('lw_login_nao/lwCreate.html.twig',[
            'form'=>$form->createView()
        ]);
    }


}

