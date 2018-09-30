<?php

namespace App\Controller;

use App\Entity\JdUsers;
use App\Form\JdUsersType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;

class JdLonginNaoController extends AbstractController
{
    /**
     * @Route("/createLogin", name="longinUser")
     */
    public function jdCreatedAtUser(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $user =  new JdUsers();
        $form = $this->createForm(JdUsersType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('jd_longin_nao/jdLogin.html.twig', [
            'form'          => $form->createView(),
        ]);
    }
}
