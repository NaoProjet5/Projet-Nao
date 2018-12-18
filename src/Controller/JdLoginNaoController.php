<?php

namespace App\Controller;

use App\Entity\JdUsers;
use App\Form\JdCompleteType;
use App\Form\JdUsersType;
use App\Form\JdValueType;
use App\Repository\JdUsersRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class JdLoginNaoController extends AbstractController
{
    /**
     * @Route("/createLogin", name="createdAtUser")
     */
    public function jdCreatedAtUser(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder, Session $session)
    {
        $session = new Session();
        $user =  new JdUsers();
        $form = $this->createForm(JdUsersType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $user->setAlive(0);
            $session->set('user', $user);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Un Email vous a été envoyer, consultez votre adresse email pour terminer votre inscription.Merci.');
            return $this->redirectToRoute('jdMailCofirme',
                [
                    'id'    => $session->get('user')->getId(),
                ]);
        }

        return $this->render('jd_login_nao/jdCreatedAtLogin.html.twig', [
            'form'          => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/mail_confirm", name="jdMailCofirme")
     */
    public function jdMailComfir(\Swift_Mailer $mailer, Session $session, Request $request)
    {
        $user = $session->get('user');
        $message = (new \Swift_Message('NAO'))
            ->setContentType('text/html')
            ->setSubject('Confirmation de votre inscription')
            ->setFrom('jobby00dev@gmail.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->render('jd_Emails/emailConfirm.html.twig',
                    [
                        'id'        => $user->getId(),
                        'email'     => $user->getEmail(),
                        'user'      => $user,

                    ])
            );
        $mailer->send($message);
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/jd_Value_Users/{id}", name="jdValueUsers")
     */

    public function jdValueUsers( JdUsers $user, ObjectManager $manager, Session $session, Request $request)
    {
        $repo = $manager;

        $form = $this->createForm(JdCompleteType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            if($user->getId() && $user->getEmail())
            {
                $user->setValide(true);
                $session->set('user', $user);
                $repo->persist($user);
                $repo->flush();
                return $this->redirectToRoute('loginUsers');
            }
        }

        return $this->render('jd_login_nao/TemplatesViews/jdValueUser.html.twig',
            [
                'form'      => $form->createView(),
                'user'      => $user->getId(),
            ]
        );
    }

    /**
     * @Route("/login/", name="loginUsers")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function jdLoginUsers(AuthenticationUtils $authenticationUtils, JdUsersRepository $repo)
    {
        $values = $repo->findBy(['valide' => true]);

        foreach ($values as $value)
        {
            $value;
        }

        if ($value->getValide() === false && $value->getEmail()!== null)
        {
            $this->addFlash('danger', 'Vous ne pouvez pas vous connectez car votre compte n\'est pas valide. Consiltez votre adresse email');
            return $this->redirectToRoute('loginUsers');
        }else
        {
            $lastUsername = $authenticationUtils->getLastUsername();
            $error = $authenticationUtils->getLastAuthenticationError();
            return $this->render('jd_login_nao/jdLogin.html.twig',
                [
                    'last_username' => $lastUsername,
                    'error'         => $error,
                ]);
        }
    }


    /**
     * @Route("/logoutUser", name="usersLogout")
     */
    public function jdLogoutUsers()
    {
        //$this->get('session')->clear();
    }
}
