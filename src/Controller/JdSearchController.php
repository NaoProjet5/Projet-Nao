<?php

namespace App\Controller;

use App\Entity\Oiseau;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class JdSearchController extends AbstractController
{
    public function jdSearchBirds()
    {
        $oiseau = new  Oiseau();

        $form = $this->createFormBuilder( $oiseau, array(
            $this->generateUrl('home').'?term=',
            'method' => 'GET',
        ) )
            ->add('Recherche d\'oiseau', null, ['label' => ' Entre le nom de l\'oisaeu'] )
            ->add('Recherche', SubmitType::class)
            ->getForm();

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
