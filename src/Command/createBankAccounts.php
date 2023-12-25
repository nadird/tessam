<?php
	// src/Command/CreateUserCommand.php
	namespace App\Command;
	
	use App\Entity\Cbien;
	use App\Entity\CFormatBien;
	use App\Entity\Commune;
	use App\Entity\Compte;
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
	use App\Entity\PaiementType;
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
	#[AsCommand(name: 'app:createBankAccounts', description: 'Arrange Areas ',)]
	class createBankAccounts extends Command
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
			if($this->entityManager->getRepository(Compte::class)->findAll()){
				$output->writeln([
					'Comptes already exist',
					'============',
					'',
				]);
				return Command::SUCCESS;
			}
			$compte = new Compte();
			$compte->setRib('000000000000000000000001');
			$compte->setIsActive(true);
			$compte->setCode('CNEP');
			$compte->setName('CNEP');
			$compte->setColor('#000000');
			$compte->addPaimentType($this->entityManager->getRepository(PaiementType::class)->findOneBy(['code'=>'CHQ']));
			$compte->addPaimentType($this->entityManager->getRepository(PaiementType::class)->findOneBy(['code'=>'VIR']));
			$compte->addPaimentType($this->entityManager->getRepository(PaiementType::class)->findOneBy(['code'=>'VCB']));
			$this->entityManager->persist($compte);
			
			$compte = new Compte();
			$compte->setRib('000000000000000000000002');
			$compte->setIsActive(true);
			$compte->setCode('BADR');
			$compte->setName('BADR');
			$compte->setColor('#000000');
			$compte->addPaimentType($this->entityManager->getRepository(PaiementType::class)->findOneBy(['code'=>'CHQ']));
			$compte->addPaimentType($this->entityManager->getRepository(PaiementType::class)->findOneBy(['code'=>'VIR']));
			$compte->addPaimentType($this->entityManager->getRepository(PaiementType::class)->findOneBy(['code'=>'VCB']));
			$this->entityManager->persist($compte);
			
			$compte = new Compte();
			$compte->setRib('000000000000000000000003');
			$compte->setIsActive(true);
			$compte->setCode('BNA');
			$compte->setName('BNA');
			$compte->setColor('#000000');
			$compte->addPaimentType($this->entityManager->getRepository(PaiementType::class)->findOneBy(['code'=>'CHQ']));
			$compte->addPaimentType($this->entityManager->getRepository(PaiementType::class)->findOneBy(['code'=>'VIR']));
			$compte->addPaimentType($this->entityManager->getRepository(PaiementType::class)->findOneBy(['code'=>'VCB']));
			$this->entityManager->persist($compte);
			
			$compte = new Compte();
			$compte->setName('Caisse chantier');
			$compte->setRib('000000000000000000000004');
			$compte->setIsActive(true);
			$compte->setCode('CAC');
			$compte->setColor('#000000');
			$compte->addPaimentType($this->entityManager->getRepository(PaiementType::class)->findOneBy(['code'=>'VCC']));
			$compte->addPaimentType($this->entityManager->getRepository(PaiementType::class)->findOneBy(['code'=>'ESP']));
			$compte->addPaimentType($this->entityManager->getRepository(PaiementType::class)->findOneBy(['code'=>'AUT']));
			$this->entityManager->persist($compte);
			
			$compte = new Compte();
			$compte->setName('Caisse Exploitation');
			$compte->setRib('000000000000000000000004');
			$compte->setIsActive(true);
			$compte->setCode('CAE');
			$compte->setColor('#000000');
			$compte->addPaimentType($this->entityManager->getRepository(PaiementType::class)->findOneBy(['code'=>'VCC']));
			$compte->addPaimentType($this->entityManager->getRepository(PaiementType::class)->findOneBy(['code'=>'ESP']));
			$compte->addPaimentType($this->entityManager->getRepository(PaiementType::class)->findOneBy(['code'=>'AUT']));
			$this->entityManager->persist($compte);
			
			$this->entityManager->flush();
			$output->writeln([
				'Created Accounts and Paiement Types',
				'============',
				'',
			]);
			
			
			
			$this->entityManager->flush();
			return Command::SUCCESS;
			
		}
		
		
		
		
		
		
		
		
		
		
		
		
	}