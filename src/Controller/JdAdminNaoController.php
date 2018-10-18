<?php

namespace App\Controller;

use App\Entity\JdUsers;
use App\Form\JdUsersType;
use App\Repository\JdUsersRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class JdAdminNaoController extends AbstractController
{
    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Route("/admin/nao/", name="jdAdminNao")
     */
    public function jdAdminNao()
    {
        return $this->render('jd_admin_nao/jdAdmin.html.twig');
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Route("/admin/usuers/nao", name="jaAllUser")
     */
    public function jdAllUsers(JdUsersRepository $repo)
    {
        $user  = $repo->findAll();
        return $this->render('jd_admin_nao/jdAdmin/jdAllUser.html.twig',
            [
                'users'      => $user
            ]);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Route("/Update/user/{id}", name="jdUpDateUser" )
     */
    public function jdUpDateUser(ObjectManager $manager, JdUsers $user, Request $request)
    {
        if ($user)
        {
            $form = $this->createForm(JdUsersType::class, $user);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid())
            {
                $manager->flush();
                return $this->redirectToRoute('jaAllUser',
                    [
                        'id'            => $user->getId()
                    ]);
            }
        }


        return $this->render('jd_login_nao/jdCreatedAtLogin.html.twig',
            [
                'form'          =>$form->createView(),
                'user'          => $user->getId(),
            ]);
    }

    /**
     * @Security("is_granted('ROLE_NATURALIST')")
     * @Route("/naturalist/nao", name="jdNaturalistNao")
     */
    public function jdNaturalist()
    {
        return $this->render('jd_admin_nao/jdNaturalist.html.twig');
    }

    /**
     * @Security("is_granted('ROLE_AUTHOR')")
     * @Route("/author/nao", name="jdAuthorNao")
     */
    public function jdAuthorNao()
    {
        return $this->render('jd_admin_nao/jdAuthor.html.twig');
    }
}
