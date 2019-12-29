<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

/**
 * Controller permettant de gérer les exceptions renvoyées par le reste de l'appli.
 */
class ExceptionController extends AbstractFOSRestController
{
    public function showAction(Request $request, $exception, DebugLoggerInterface $logger = null)
    {
        $classe = $exception->getClass();
        $code = $exception->getCode() ? $exception->getCode() : null;

        // Gestion manuelle du code de retour des EntityNotFoundException.
        if ($classe === 'Doctrine\ORM\EntityNotFoundException') {
            $code = 404;
        }
        $view = $this->view(new $classe($exception->getMessage()), $code);

        return $this->handleView($view);
    }
}