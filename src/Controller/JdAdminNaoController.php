<?php

namespace App\Controller;

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
