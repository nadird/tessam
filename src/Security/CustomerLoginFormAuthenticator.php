<?php
	
	namespace App\Security;
	
	use App\Entity\Customer;
	use App\Entity\Employee;
	use App\Repository\CustomerRepository;
	use App\Repository\EmployeeRepository;
	use Symfony\Bundle\SecurityBundle\Security;
	use Symfony\Component\HttpFoundation\RedirectResponse;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
	use Symfony\Component\Routing\RouterInterface;
	use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
	use Symfony\Component\Security\Core\Exception\AuthenticationException;
	use Symfony\Component\Security\Core\Exception\UserNotFoundException;
	use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
	use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
	use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
	use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
	use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
	use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
	use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
	use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
	use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
	use Symfony\Component\Security\Http\Util\TargetPathTrait;
	
	class CustomerLoginFormAuthenticator extends AbstractLoginFormAuthenticator
	{
		use TargetPathTrait;
		public function __construct(
			//private EmployeeRepository $employeeRepository,
			private CustomerRepository $customerRepository,
			private UserPasswordHasherInterface $passwordHasher,
			private RouterInterface $router
		){
		}
		public function supports(Request $request): bool
		{
			return $request->getPathInfo() === '/customer/login' && $request->isMethod('POST');
		}
		
		public function authenticate(Request $request): Passport
		{
			$email = $request->request->get('email', '');
			$password= $request->request->get('password', '');
			if($email === '' || $password === '') {
				throw new AuthenticationException('Missing credentials');
			}
			
			
			
			return new Passport(
				new UserBadge($email, function($userIdentifier) {
					$user= $this->customerRepository->findOneBy(['email' => $userIdentifier]);
					if (!$user) {
						throw new UserNotFoundException();
					}
					if(!$user instanceof Customer) {
						throw new \LogicException('You are not allowed to connect here');
					}
					if($user->isIsActive() === false) {
						throw new AuthenticationException('Account is not active, please ask administrator to activate your account');
					}
					return $user;
				}),
				
				new PasswordCredentials($password),[
					new csrfTokenBadge(
						'authenticate',
						$request->get('_csrf_token')),
					(new RememberMeBadge())->enable(),
				]
			
			);
			
		}
		
		public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): RedirectResponse
		{
			//$targetPath= $request->getSession()->get('_security.'.$firewallName.'.target_path');
			if($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
				return new RedirectResponse($targetPath);
			}
			return new RedirectResponse($this->router->generate('app_customer_index'));
		}
		
		public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
		{
			$request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
			return new RedirectResponse($this->router->generate('app_customer_login'));
			
		}
		public function start(Request $request, AuthenticationException $authException = null): Response
		{
			return new RedirectResponse($this->router->generate('app_customer_login'));
		}
		
		protected function getLoginUrl(Request $request): string
		{
			return $this->router->generate('app_customer_login');
		}
	}
