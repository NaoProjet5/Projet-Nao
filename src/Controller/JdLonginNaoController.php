<?php

namespace App\Controller;

use App\Entity\JdUsers;
use App\Form\JdUsersType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class JdLonginNaoController extends AbstractController
{
    /**
     * @Route("/createLogin", name="longinUser")
     */
    public function jdCreatedAtUser(Request $request)
    {
        $user =  new JdUsers();
        $form = $this->createForm(JdUsersType::class, $user);
        //$form->handleRequest();//


        return $this->render('jd_longin_nao/jdLogin.html.twig', [
            'form'          => $form->createView(),
        ]);
    }
}
