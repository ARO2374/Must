<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Service\SecuriteService;

class SecuriteController extends FOSRestController
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
        return $this->securiteService->gererToken($request->request->get('username'), $request->request->get('password'));
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

    // /**
    //  * @Route(name="api_login", path="/api/login_check")
    //  * @return JsonResponse
    //  */
    // public function api_login(): JsonResponse
    // {
    //     $user = $this->getUser();

    //     return new Response([
    //         'email' => $user->getEmail(),
    //         'roles' => $user->getRoles(),
    //     ]);
    // }

    // /**
    //  * @Route("/login", name="app_login")
    //  */
    // public function login(AuthenticationUtils $authenticationUtils): Response
    // {
    //     // get the login error if there is one
    //     $error = $authenticationUtils->getLastAuthenticationError();
    //     // last username entered by the user
    //     $lastUsername = $authenticationUtils->getLastUsername();

    //     return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    // }

    // /**
    //  * @Route("/logout", name="app_logout", methods={"GET"})
    //  */
    // public function logout()
    // {
    //     // controller can be blank: it will never be executed!
    //     throw new \Exception('Don\'t forget to activate logout in security.yaml');
    // }
}
