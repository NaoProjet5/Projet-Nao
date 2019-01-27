<?php

namespace App\Controller;

use App\Entity\Oiseau;
use App\Form\JdCompleteType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class JdSearchController extends AbstractController
{

    public function jdSearchBirds()
    {
        $oiseau = new  Oiseau();

        $form = $this->createForm( JdCompleteType::class, $oiseau);


        return $this->render('jd_search/jdSearch.html.twig',
            [
                'form'      => $form->createView()
            ]
        );

    }
    /**
     * @Route("/search/single/bird", name="search_oiseau", defaults={"_format"="json"}, methods={"POST", "GET"})
     */
    public function searchBird(Request $request)
    {
        $q = $request->query->get('term'); // use "term" instead of "q" for jquery-ui
        $results = $this->getDoctrine()->getRepository(Oiseau::class)->findLike($q);

        return $this->render('jd_search/jdOneBird.html.twig',
            [
                'results'       => $results
            ]);
    }
}
