<?php
	// src/Command/CreateUserCommand.php
	namespace App\Command;
	
	use App\Entity\CFormatBien;
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
	#[AsCommand(name: 'app:fake_prospectUpdate', description: 'Creates fake Orders ',)]
	class FakeDataProspectUpdate extends Command
	{
		public function __construct(
			private \Doctrine\ORM\EntityManagerInterface $entityManager,
			//ParameterBagInterface $params,
		
		){
			//$this->params = $params;
			parent::__construct();
		}
		
		protected function execute(InputInterface $input, OutputInterface $output): int
		{
			$leads = $this->entityManager->getRepository(Lead::class)->findAll();
			$employees= $this->entityManager->getRepository(Employee::class)->findAll();
			$i=0;
			foreach($leads as $lead){
				if($lead->getCustomer() !==null){
					$lead->setState($this->entityManager->getRepository(LeadState::class)->findOneBy(['code'=>'WON']));
					if($lead->getEmployee() ==null) {
						$lead->setEmployee($employees[array_rand($employees)]);
					}
				}else{
					if($lead->getEmployee() !==null){
						if($i%2==0) {
							$lead->setState($this->entityManager->getRepository(LeadState::class)->findOneBy(['code' => 'QUAL']));
						}else{
							$lead->setState($this->entityManager->getRepository(LeadState::class)->findOneBy(['code' => 'LOST']));
						}
					}else{
						if($i%2==0) {
							$lead->setState($this->entityManager->getRepository(LeadState::class)->findOneBy(['code' => 'AFF']));
							$lead->setEmployee($employees[array_rand($employees)]);
						}else{
							continue;
						}
					}
				}
				$this->entityManager->persist($lead);
				$i++;
			}
			$this->entityManager->flush();
			
			$output->writeln('Fake Orders created');
			
			
			return Command::SUCCESS;
			
		}
		public function addRandomdays($date): \DateTime
		{
			
			
			$interval = new \DateInterval('P1W');
			$date->add($interval);
			return $date;
		}
		
		public function randomDay(): \DateTime
		{
			$faker = Factory::create('fr_FR');
			$date = $faker->dateTimeBetween('-3 months', '+3 days');
			return $date;
		}
		
		
	}