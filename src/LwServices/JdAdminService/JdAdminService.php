<?php

namespace App\LwServices\JdAdminService;


class JdAdminService
{

    public function jdRoles()
    {
        $roles = '';
        if(empty($roles) && $roles = 'Naturaliste')
        {
             $roles = 'ROLE_NATURALIST';
        }
        if(empty($roles) && $roles = 'Editeur')
        {
            $roles = 'ROLE_AUTHOR';
        }
        if(empty($roles) && $roles = 'Super admin')
        {
            $roles = 'ROLE_SUPER_ADMIN';
        }else
        {
            $roles = 'ROLE_USER';
        }
        return $roles;
    }
}