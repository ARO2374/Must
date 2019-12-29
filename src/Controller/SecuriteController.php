<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use App\Service\SecuriteService;

class SecuriteController extends AbstractFOSRestController
{
    /**
     * @var $securiteService
     */
    private $securiteService;

    /**
     * Constructeur du SecuriteController.
     * @param securiteService $securiteService
     */
    public function __construct(SecuriteService $securiteService)
    {
        $this->securiteService = $securiteService;
    }

    /**
     * Connecte un utilisateur si les login/pwd sont corrects.
     * @param $request La requête de l'appel HTTP.
     * @return Un token.
     * 
     * @Post("api/login")
     * @View
     */
    public function loginAction(Request $request): string
    {
        return $this->securiteService->genererToken($request->request->get('username'), $request->request->get('password'));
    }

    /**
     * Déconnecte un utilisateur.
     * @param $request La requête de l'appel HTTP.
     * 
     * @Get("api/logout")
     * @View
     */
    public function logoutAction(Request $request): void
    {
        $this->securiteService->supprimerToken($request->query->get('username'));
    }
}
