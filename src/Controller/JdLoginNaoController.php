<?php

namespace App\Controller;

use App\Entity\JdUsers;
use App\Form\JdUsersType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class JdLoginNaoController extends AbstractController
{
    /**
     * @Route("/createLogin", name="createdAtUser")
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
            $user->setAlive(0);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('jd_login_nao/jdCreatedAtLogin.html.twig', [
            'form'          => $form->createView()
        ]);
    }

    public function jdValueUsers()
    {
        
    }

    /**
     * @Route("/login", name="loginUsers")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function jdLoginUsers(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('jd_login_nao/jdLogin.html.twig', [
                'last_username' => $lastUsername,
                'error'         => $error,
            ]);
    }

    /**
     * @Route("/logoutUser", name="usersLogout")
     */
    public function jdLogoutUsers(){}
}
