<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class JdUserNaoController extends AbstractController
{
    /**
     * @Route("/jd/user/nao", name="jd_user_nao")
     */
    public function index()
    {
        return $this->render('jd_user_nao/index.html.twig', [
            'controller_name' => 'JdUserNaoController',
        ]);
    }
}
