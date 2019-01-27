<?php
/**
 * Created by PhpStorm.
 * User: wlandry
 * Date: 18/12/18
 * Time: 13:33
 */

namespace App\LwServices;
use http\Url;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
         $url = $request->getUri();
        $url = trim($url, "/admin/nao/");
        $content = '<html>
   <head> <title> PROJET NAO</title>
   <style type="text/css">
   body .droit_acces {
   width: 70%;
   height: 15%;
   margin-top: 15%;
   text-align: center;
   background-color: #08ACDE;
   color: #FFFFFF;
   border-radius: 1.5%;
   margin-left: 15%;
   }
</style>
   </head><body><div class="droit_acces"><h1> OUP !!! Vous ne possédez pas les droits d\'accès requis pour cette fonctionnalité !!!</h1> <a href="
   '.$url.'">Retour au site NAO</a></div></body></html>';

        return new Response($content, 403);
    }
}