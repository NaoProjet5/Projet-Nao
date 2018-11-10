<?php

namespace App\Controller;

use App\Entity\LwArticle;
use App\Form\LwArticleType;
use App\Repository\LwArticleRepository;
use App\Repository\ObservationRepository;
use Doctrine\Common\Persistence\ObjectManager;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Geocoder\Query\GeocodeQuery;
use Geocoder\Query\ReverseQuery;
use Doctrine\ORM\Tools\Pagination\Paginator;
use App\LwServices\FileUploader;

class LwController extends AbstractController
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
     * @Route ("/lw/article/{id}",name="blog_show")
     */
    public function show_article(LwArticle $article){
        return $this->render('lw_pub_nao/lwOneArticle.html.twig',[
            'article'=>$article
        ]);

    }

    /**
     * @route ("/lw/AdminObservation", name="admin_observation")
     */
    public function adminObservation(ObservationRepository $repos){
        $observation = $repos->findAll();
        /*$paginator = new Paginator($observation);
        $c = count($paginator);
        dump($paginator);
        dump($c);
        die();*/
        return $this->render('lw_login_nao/lwAdminObservation.html.twig',[
            'observations'=>$observation
        ]);

    }
    /**
     * @route ("/lw/AdminArticle", name="admin_article")
     */
    public function adminArticle( LwArticleRepository $repos){
        $article = $repos->findAll();
        return $this->render('lw_login_nao/lwAdminArticle.html.twig',[
            'articles' => $article
        ]);
    }
    /**
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
        return $this->render('jd_pub_nao/Public/contact.html.twig');
    }


}
