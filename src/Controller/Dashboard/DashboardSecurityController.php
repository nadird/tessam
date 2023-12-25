<?php

namespace App\Controller\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class DashboardSecurityController extends AbstractController
{
    public function __construct(private AuthenticationUtils $authenticationUtils)
    {
    }

    #[Route('/dashboard/login', name: 'app_dashboard_login')]
    public function login(): Response
    {

          $error = $this->authenticationUtils->getLastAuthenticationError();
        /*
         * //dd($error);
        if ($error !== null && $error instanceof AuthenticationException) {
            // Handle the authentication error
            $errorMessage = $error->getMessage();
        } else {
            $errorMessage = null;
        }
         */

        if ($this->getUser()) {
            return $this->redirectToRoute('app_dashboard_home_index');
        }

        return $this->render('dashboard/dashboard_security/login.html.twig', [
            'controller_name' => 'Login',
            'error' => $error,
        ]);
    }
    #[Route('/dashboard/logout', name: 'app_dashboard_logout')]
    public function logout(): Response
    {
        throw new \Exception('This should never be reached!');

        return $this->render('dashboard/dashboard_security/login.html.twig', [
            'controller_name' => 'Logout',
        ]);
    }
}
