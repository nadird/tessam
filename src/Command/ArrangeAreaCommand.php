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
	#[AsCommand(name: 'app:arrangeArea', description: 'Arrange Areas ',)]
	class ArrangeAreaCommand extends Command
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
			$biens = $this->entityManager->getRepository(Cbien::class)->findAll();
			foreach ($biens as $bien){
				$area= $bien->getExactArea();
				$bien->setUtileArea($area*0.85);
				$this->entityManager->persist($bien);
			}
			$this->entityManager->flush();
			return Command::SUCCESS;
			
		}
		
		
		
		
		
		
		
		
		
		
		
		
	}