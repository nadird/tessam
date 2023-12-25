<?php
	
	namespace App\Controller\Customer;
	
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;
	use Symfony\Component\Security\Core\Exception\AuthenticationException;
	use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
	
	class CustomerSecurityController extends AbstractController
	{
		public function __construct(private AuthenticationUtils $authenticationUtils)
		{
		}
		
		#[Route('/customer/login', name: 'app_customer_login')]
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
				return $this->redirectToRoute('app_customer_index');
			}
			
			return $this->render('customer/customer_security/login.html.twig', [
				'controller_name' => 'Login',
				'error' => $error,
			]);
		}
		#[Route('/customer/logout', name: 'app_customer_logout')]
		public function logout(): Response
		{
			throw new \Exception('This should never be reached!');
			
			return $this->render('dashboard/dashboard_security/login.html.twig', [
				'controller_name' => 'Logout',
			]);
		}
	}
