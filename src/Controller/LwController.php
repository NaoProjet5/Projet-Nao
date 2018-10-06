<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
}
