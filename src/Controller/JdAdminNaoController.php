<?php

namespace App\Controller;

use App\Entity\JdUsers;
use App\Form\JdAddAdminType;
use App\Form\JdUpUsersType;
use App\Form\JdUsersType;
use App\Repository\JdUsersRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\LwServices\JdAdminService\JdAdminService;


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
     * @Route("/admin/users/nao", name="jaAllUser")
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
    public function jdUpDateUser(ObjectManager $manager, JdUsers $user, Request $request, UserPasswordEncoderInterface $encoder)
    {
        if ($user)
        {
            $form = $this->createForm(JdUpUsersType::class, $user);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                $hash = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($hash);
                $role = $user->getRoles();
                $user->setRoles($role);
                $manager->flush();
                return $this->redirectToRoute('jaAllUser',
                    [
                        'id'            => $user->getId()
                    ]);
            }
        }

        return $this->render('jd_login_nao/TemplatesViews/jdUpDateUser.html.twig',
            [
                'form'          =>$form->createView(),
                'user'          => $user->getId(),
            ]);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Route("/add/user/nao", name="jdadduserNao")
     */
    public function jdAddUser(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $user =  new JdUsers();
        $form = $this->createForm(JdAddAdminType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $role = $user->getRoles();
            $user->setRoles($role);
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('jaAllUser');
        }

        return $this->render('jd_login_nao/TemplatesViews/jdAddUser.html.twig', [
            'form'          => $form->createView(),
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
