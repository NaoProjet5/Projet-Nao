<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\LwArticle;
use App\Form\CommentType;
use App\Form\LwArticleType;
use App\Repository\CommentRepository;
use App\Repository\LwArticleRepository;
use App\Repository\ObservationRepository;
use Doctrine\Common\Persistence\ObjectManager;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\Tools\Pagination\Paginator;
use App\LwServices\FileUploader;
use Welp\MailchimpBundle\Event\SubscriberEvent;
use Welp\MailchimpBundle\Subscriber\Subscriber;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LwController extends Controller
{
    /**
     * @Route("/lw", name="lw")
     */
    public function index()
    {
        return $this->render('lw/index.html.twig', [
            'controller_name' => 'LwController',
        ]);
    }

    /**
     * @Route("/lw/Home/Admin", name="lwHomeAdmin")
     */
    public function HomeAdmin()
    {
        return $this->render('lw_login_nao/HomeAdmin.html.twig', [

        ]);
    }



    /**
     * @Route ("/lw/article/{id}",name="blog_show")
     */
    public function show_article(LwArticle $article){
        return $this->render('lw_pub_nao/lwOneArticle.html.twig',[
            'article'=>$article
        ]);

    }

    /**
     * @Security("is_granted('ROLE_NATURALIST')")
     * @route ("/lw/AdminObservation", name="admin_observation")
     */
    public function adminObservation(ObservationRepository $repos,Request $request){
        $observation = $repos->findAll();
        /* @var $paginator \Knp\Component\Pager\Paginator */
        $paginator  = $this->get('knp_paginator');
        // Paginate the results of the query
        $appointments = $paginator->paginate(
        // Doctrine Query, not results
            $observation,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            3
        );
        return $this->render('lw_login_nao/lwAdminObservation.html.twig',[
            'observations'=>$appointments
        ]);

    }
    /**
     * @Security("is_granted('ROLE_NATURALIST')")
     * @route ("/lw/adminObservationAccept", name="observation_accept")
     */
    public function adminObservationValide(ObservationRepository $repos){
        $observation = $repos->findBy(['valide'=>1]);
        return $this->render('lw_login_nao/lwAdminObservationValide.html.twig',[
            'observations'=>$observation
        ]);

    }
    /**
     * @Security("is_granted('ROLE_NATURALIST')")
     * @route ("/lw/adminObservationRef", name="observation_ref")
     */
    public function adminObservationInvalide(ObservationRepository $repos){
        $observation = $repos->findBy(['valide'=>0]);
        return $this->render('lw_login_nao/lwAdminObservationInvalide.html.twig',[
            'observations'=>$observation
        ]);

    }
    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @route ("/lw/AdminArticle", name="admin_article")
     */
    public function adminArticle( LwArticleRepository $repos, Request $request){
        $article = $repos->findAll();
        /* @var $paginator \Knp\Component\Pager\Paginator */
        $paginator  = $this->get('knp_paginator');
        // Paginate the results of the query
        $appointments = $paginator->paginate(
        // Doctrine Query, not results
            $article,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );
        return $this->render('lw_login_nao/lwAdminArticle.html.twig',[
            'articles' => $appointments
        ]);
    }
    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @route ("/lw/UpdateArticle/{id}", name="update_article")
     */
    public function updateArticle(LwArticle $article, ObjectManager $manager, Request $request, FileUploader $fileUploader){

        $form = $this->createForm(LwArticleType::class, $article);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $article->setCreatedAt(new \DateTime());
            $article->setAlive(1);
            $article->setUsers($this->getUser());
            if (!empty($form->get('photo')->getData())){
                $file = $form->get('photo')->getData();
                $fileName = $fileUploader->upload($file);
                $article->setPhoto($fileName);
            }
            $manager->persist($article);
            $manager->flush();
            return $this->redirectToRoute('admin_article');
        }
        return $this->render('lw_login_nao/lwUpdateArticle.html.twig',
            [
                'form'          =>$form->createView(),
                'article'          => $article->getId(),
            ]);

    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Route("/lw/valide/{id}",name="valide_observation")
     */
    public function valideObservation($id, ObservationRepository $repos, ObjectManager $manager){
        $observation = $repos->find($id);
        if ($observation->getValide() == 0 ){
            $observation->setValide(1);
        }
        else{
            $observation->setValide(0);
        }

        $manager->flush();
        return $this->redirectToRoute('admin_observation');
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @route ("/lw/removeObservation/{id}", name="remove_observation")
     */
    public function removeObservation( $id, ObservationRepository $repos, ObjectManager $manager){
        $observation = $repos->find($id);

        $manager->remove($observation);
        $manager->flush();
        return $this->redirectToRoute('admin_observation');
    }

    /**
     * @route ("/lw/gpsdata", name="gpsdata" )
     */
    public function getDataObservation( ObservationRepository $repos, ObjectManager $manager, Request $request){

        $oiseaux =  $request->request->get('bird_name');
        $observation = $repos->getGpsData($oiseaux);
        $dataLong = array();
        $dataLat = array();
        $dataId = array();

        foreach ($observation as $key=>$valeur){
            $dataLong[$key] = $valeur->getLongitude();
            $dataLat[$key] = $valeur->getLatitude();
            $dataId[$key] = $valeur->getOiseau()->getId();

        }

        return new JsonResponse(array($dataLong,$dataLat,$dataId));
    }

    /**
     * @route ("/lw/contact", name="contactNao")
     */
    public function contactNao()
    {
        $this->addFlash('notice','Merci pour votre message nous vous repondons dans un délais proche !!!');
        return $this->render('jd_pub_nao/Public/contact.html.twig');
    }
    /**
     * @route("/lw/news_letter",name="newsLetter")
     */
    public function newsLetterNao(Request $request, EventDispatcherInterface $dispatcher ){
        $subscriber = new Subscriber($request->request->get('email'),[
            'language'=>'fr'
        ]);

        $dispatcher->dispatch(
            SubscriberEvent::EVENT_SUBSCRIBE,
            new SubscriberEvent("f547f5ff8f",$subscriber)
        );
        return $this->redirectToRoute('home');
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @route("/lw/admin_comment",name="AdminComment")
     */
    public function AdminComment( CommentRepository $repos, Request $request){
        $comment = $repos->findAll();
        /* @var $paginator \Knp\Component\Pager\Paginator */
        $paginator  = $this->get('knp_paginator');
        // Paginate the results of the query
        $appointments = $paginator->paginate(
        // Doctrine Query, not results
            $comment,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );
        return $this->render('lw_login_nao/lwAdminAllComment.html.twig',[
            'comments' => $appointments
        ]);
    }
    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @route("/lw/admin_commentSignal",name="AdminCommentSignal")
     */
    public function AdminCommentSignal( CommentRepository $repos, Request $request){
        $comment = $repos->findBy(['signale'=>1]);
        /* @var $paginator \Knp\Component\Pager\Paginator */
        $paginator  = $this->get('knp_paginator');
        // Paginate the results of the query
        $appointments = $paginator->paginate(
        // Doctrine Query, not results
            $comment,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );
        return $this->render('lw_login_nao/lwAdminCommentSignal.html.twig',[
            'comments' => $appointments
        ]);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @route ("/lw/UpdateComment/{id}", name="update_comment")
     */
    public function updateComment(Comment $comment, ObjectManager $manager, Request $request){

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($comment);
            $manager->flush();
            return $this->redirectToRoute('AdminComment');
        }
        return $this->render('lw_login_nao/lwUpdateComment.html.twig',
            [
                'form'          =>$form->createView(),
                'comment'          => $comment->getId(),
            ]);

    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @route ("/lw/removeComment/{id}", name="remove_comment")
     */
    public function removeComment( $id, CommentRepository $repos, ObjectManager $manager){
        $comment = $repos->find($id);

        $manager->remove($comment);
        $manager->flush();
        return $this->redirectToRoute('AdminComment');
    }

    /**
     * @route ("/lw/restoreComment/{id}", name="restore_comment")
     */
    public function restoreComment( $id, CommentRepository $repos, ObjectManager $manager){
        $comment = $repos->find($id);
        $comment->setSignale(0);
        $manager->persist($comment);
        $manager->flush();
        return $this->redirectToRoute('AdminComment');
    }
    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @route ("/lw/changeArticle/{id}", name="change_article")
     */
    public function changeArticle( $id, lwArticleRepository $repos, ObjectManager $manager){
        $article = $repos->find($id);
        $article->setAlive(0);

        $manager->persist($article);
        $manager->flush();
        return $this->redirectToRoute('trashArticle');
    }
    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @route ("/lw/removeArticle/{id}", name="comment_observation")
     */
    public function removeArticle( $id, lwArticleRepository $repos, ObjectManager $manager){
        $article = $repos->find($id);

        $manager->remove($article);
        $manager->flush();
        return $this->redirectToRoute('admin_article');
    }
    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @route("/lw/admin_publicArticle",name="publicArticle")
     */
    public function publicArticle( LwArticleRepository $repos, Request $request){
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
            5
        );
        return $this->render('lw_login_nao/lwPublicArticle.html.twig',[
            'articles' => $appointments
        ]);
    }
    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @route("/lw/admin_trashArticle",name="trashArticle")
     */
    public function trashArticle( LwArticleRepository $repos, Request $request){
        $article = $repos->findBy(['alive'=>0]);
        /* @var $paginator \Knp\Component\Pager\Paginator */
        $paginator  = $this->get('knp_paginator');
        // Paginate the results of the query
        $appointments = $paginator->paginate(
        // Doctrine Query, not results
            $article,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );
        return $this->render('lw_login_nao/lwTrashArticle.html.twig',[
            'articles' => $appointments
        ]);
    }
    /**
     * @Route("/article/{id}", name="oneArticle")
     */
    public function lwOneArticle(Request $request, ObjectManager $manager, LwArticle $article){
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $comment->setCreatedAt(new \DateTime());
            $user = $this->getUser();
            $comment->setAuthor($user);
            $comment->setSignale(0);
            $comment->setArticle($article);
            $manager->persist($comment);
            $manager->flush();
            $this->addFlash('notice_com','Nous vous remercions pour votre commentaire !!!');
            return $this->redirectToRoute('oneArticle',['id'=>$article->getId()]);
        }

        return $this->render('jd_pub_nao/Public/lwArticle.html.twig',[
            'article'=>$article,
            'formComment'=>$form->createView()
        ]);
    }





}
