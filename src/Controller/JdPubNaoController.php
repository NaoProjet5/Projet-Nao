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
use App\Repository\LwArticleRepository;
use App\Repository\ObservationRepository;
use App\Repository\OiseauRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


class JdPubNaoController extends AbstractController
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
    public function jdAllBirds(OiseauRepository $repos)
    {
        $oiseau = $repos->findAll();
        return $this->render('jd_pub_nao/Public/jdMapBirds.html.twig',[
            'oiseaux'=>$oiseau
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
            return $this->redirectToRoute('bird',['id'=>$oiseau->getId()]);
        }
        return $this->render('lw/observation.html.twig',[
            'formObservation'=>$form->createView(),
            'oiseau'=>$oiseau
        ]);
    }

    /**
     * @Route("/allArticles", name="blog")
     */
    public function jdAllArticles(LwArticleRepository $repos)
    {
        $article = $repos->findBy(['alive'=>1]);
        return $this->render('jd_pub_nao/Public/jdAllArticles.html.twig',[
            'articles'=>$article
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


}

