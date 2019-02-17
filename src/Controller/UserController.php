<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\JdUpDatePassWordUserType;
use App\Form\JdUpdateUserType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UserController extends AbstractController
{
    /**
     * @Security("is_granted('ROLE_USER')")
     * @Route("/paramtres", name="settingsUser")
     */
    public  function jdSettingsUser()
    {
        $user = $this->getUser();
        return $this->render('jd_user_nao/jdSettings.html.twig',
            [
                'user'      => $user,
            ]);
    }
    /**
     * @Security("is_granted('ROLE_USER')")
     * @Route("/modifier", name="updateUser")
     */
    public function jdUpdateUser(Request $request, ObjectManager $manager)
    {
        $user = $this->getUser();
        $form = $this->createForm( JdUpdateUserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->flush();
            return $this->redirectToRoute('settingsUser');
        }
        return $this->render('jd_user_nao/jdUpdateUser.html.twig',
            [
                'user'      => $user,
                'form'      => $form->createView(),
            ]);
    }
    /**
     * @Security("is_granted('ROLE_USER')")
     * @Route("/changer-mot-de-passe", name="UpdetePassWordUser")
     */
    public function jdUpDatePassWord(ObjectManager $manager,Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = $this->getUser();
        $em = $manager;
        $form = $this->createForm(JdUpDatePassWordUserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $em->persist($user);
            $em->flush();
            $this->addFlash('notice', 'Votre mot de passe à bien été changé !');
            return $this->redirectToRoute('settingsUser');
        }
        return $this->render('jd_user_nao/jdUpDatePassWordUser.html.twig',
            [
                'form'          => $form->createView(),
            ]);
    }
}
