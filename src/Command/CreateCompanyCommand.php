<?php
	// src/Command/CreateUserCommand.php
	namespace App\Command;
	
	use App\Entity\Cbien;
	use App\Entity\CFormatBien;
	use App\Entity\Commune;
	use App\Entity\ContactJob;
	use App\Entity\Customer;
	use App\Entity\CustomerState;
	use App\Entity\Employee;
	use App\Entity\InteractionHistory;
	use App\Entity\InteractionSource;
	use App\Entity\InteractionType;
	use App\Entity\Lead;
	use App\Entity\LeadSource;
	use App\Entity\LeadState;
	use App\Entity\TheCompany;
	use App\Entity\TraficTracking;
	use App\Entity\Visitor;
	use App\Entity\Wilaya;
	use Faker\Factory;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\Console\Attribute\AsCommand;
	use Symfony\Component\Console\Command\Command;
	use Symfony\Component\Console\Input\InputInterface;
	use Symfony\Component\Console\Output\OutputInterface;
	use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
	use function PHPUnit\Framework\isInstanceOf;
	use function PHPUnit\Framework\isType;

// the name of the command is what users type after "php bin/console"
	#[AsCommand(name: 'app:createComnay', description: 'Arrange Areas ',)]
	class CreateCompanyCommand extends Command
	{
		public function __construct(
			private \Doctrine\ORM\EntityManagerInterface $entityManager,
			ParameterBagInterface $params,
		
		){
			$this->params = $params;
			parent::__construct();
		}
		
		protected function execute(InputInterface $input, OutputInterface $output): int
		{
			$output->writeln([
				'Start',
				'============',
				'',
			]);
			if(!$this->entityManager->getRepository(TheCompany::class)->findAll()){
				$output->writeln([
					'Company already exist',
					'============',
					'',
				]);
				return Command::SUCCESS;
			}
			$company = new TheCompany();
			$company->setName('AffiniaProm');
			$company->setAddress('Cité 1000 logements, Bt 10, N° 02, 1er étage, Bir El Djir, Oran');
			$company->setLogoPrint(' ');
			$company->setLogoMainDark('');
			$company->setLogoMainLight('');
			$company->setLogoMobileDark('');
			$company->setLogoMobileLight('');
			$company->setDiplayPrices(true);
			$company->setLocalisation(' ');
			$company->setRaisonSocial('SARL EVACT ALGERIE ');
			$company->setEmail1('contact@affinia.pro');
			$company->setPhone1('+213 560 00 00 00');
			$company->setPhone2('+213 660 00 00 00');
			$company->setEmail2('administration@evact.net');
			$company->setShortDescription(' ');
			$company->setAgrement('2020202');
			$company->setAgrementDate(new \DateTime());
			$company->setFacebookLink('https://www.facebook.com/affiniaprom');
			$company->setFacebookPixel('303030003003');
			$company->setFavicon(' ');
			$company->setImageHome(' ');
			$company->setOgImage(' ');
			$company->setVideoHome(' ');
			$company->setLongDescription(' ');
			$this->entityManager->persist($company);
			
			$this->entityManager->flush();
			$output->writeln([
				'Created the main company',
				'============',
				'', $company->getName(),
			]);
		
			return Command::SUCCESS;
			
		}
		
		
		
		
		
		
		
		
		
		
		
		
	}