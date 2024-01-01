<?php
	namespace App\Twigextensions;
	use App\Entity\TheCompany;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
	use Symfony\Component\HttpFoundation\ParameterBag;
	use Twig\Extension\AbstractExtension;
	use Twig\Extension\GlobalsInterface;
	use Twig\TwigFunction;
	use Symfony\Component\HttpFoundation\RequestStack;
	use Symfony\Component\HttpFoundation\Session\SessionInterface;
	
	
	class GlobalTwigVariables extends AbstractExtension implements GlobalsInterface
	{
		public function __construct(
			private EntityManagerInterface $em,
			private ParameterBagInterface $params,
			){
			
		}
		
		public function getFunctions(): array
		{
			return [
				new TwigFunction('getGlobalVariables', [$this, 'getGlobalVariables']),
			];
		}
		
		public function getGlobalVariables()
		{
			
			$globalVariables = [
				'TheCompany' => $this->em->getRepository(TheCompany::class)->findOneBy(['id' => 1]),
				'version' => $this->params->get('app.version'),
			];
			return $globalVariables;
		}
		
		public function getGlobals(): array
		{
			return [
				'globalVariables' => $this->getGlobalVariables(),
			];
		}
	}