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
	use Symfony\Component\Uid\UuidV4;
	use function PHPUnit\Framework\isInstanceOf;
	use function PHPUnit\Framework\isType;

// the name of the command is what users type after "php bin/console"
	#[AsCommand(name: 'app:fake_leads', description: 'Creates fakeleads ',)]
	class FakeDataLeadsCommand extends Command
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
				'Fake Leads',
				'============',
				'',
			]);
			$projectDir = $this->params->get('kernel.project_dir');
			$filePath = $projectDir . '/datainstall/FakeData.csv';
			$handle = fopen($filePath, 'rb');
			$output->writeln([
				'Opened file',
				'============',
				'',
			]);
			if (!$handle) {
				//throw $this->createNotFoundException('File not found.');
			}
			$data = [];
			while (($row = fgetcsv($handle, 100, ";")) !== FALSE) {
				$row = array_map(function($value) {
					return mb_check_encoding($value, 'UTF-8') ? $value : mb_convert_encoding($value, 'UTF-8');
				}, $row);
				$data[] = $row;
			}
			fclose($handle);
			$output->writeln([
				'Got data',
				'============',
				'',
			]);
			
			$noms = [];
			$prenoms = [];
			$phones = [];
			
			foreach ($data as $datum) {
				$noms[] = trim($datum[0])?trim($datum[0]):'test';
				$prenoms[] = trim($datum[1])?trim($datum[1]):'test';
				$phones[] = trim($datum[2])?trim($datum[2]):'test';
			}
			
			$output->write('Got names and phones');
			$newLeads = [];
		
			for ($i = 0; $i < 100; $i++) {
				$rand1 = rand(0, count($noms) - 1);
				$rand2 = rand(0, count($prenoms) - 1);
				$rand3 = rand(0, count($phones) - 1);
				$nom = $noms[$rand1];
				$prenom = $prenoms[$rand2];
				$phone = $phones[$rand3];
				$lead = $this->createLead($nom, $prenom, $phone);
				$output->writeln('Created lead'.$lead->getId().' '.$lead->getFirstName().' '.$lead->getLastName());
				$newLeads[] = $lead;
				if($i==20||$i==40||$i==60||$i==80||$i==100){
					$this->entityManager->flush();
					$output->writeln('flush new lead');
				}
				
				
				
			}
			
			
			foreach ($newLeads as $lead) {
				$output->writeln('Processing lead'.$lead->getId().' '.$lead->getFirstName().' '.$lead->getLastName());
				$chance = rand(1, 3);
				if ($chance > 1) { // This will be true for 2 and 3
					$this->processTypeTwo($lead);
					$output->writeln('Type 2');
				} else { // This will be true for 1
					$this->processTypeThree($lead);
					$output->writeln('Type 3');
				}
				
				}
					$this->entityManager->flush();
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
		public function createLead($nom, $prenom, $phone): Lead
		{
			$lead = new Lead();
			$dayCreated= $this->randomDay();
			$dates[] = $dayCreated;
			$lead->setDateAdd($dayCreated);
			$lead->setDateUpdated($dayCreated);
			$lead->setLastInteraction($dayCreated);
			$lead->setFirstname($nom);
			$lead->setLastname($prenom);
			$lead->setPhone1($phone);
			$lead->setEmail1($lead->getFirstName() . '.' . $lead->getLastName() . '@gmail.com');
			$lead->setIsDeleted(false);
			$lead->setSource($this->entityManager->getRepository(LeadSource::class)->find(rand(1, 5)));
			$lead->setIsValid(rand(0, 1));
			$lead->setJob(
				$this->entityManager->getRepository(ContactJob::class)->find(rand(1, 5))
			);
			$lead->addFormatBienInterest(
				$this->entityManager->getRepository(CFormatBien::class)->find(rand(1, 14))
			);
			$lead->setIsProcessed(false);
			$lead->setIsDeleted(false);
			$lead->setIsTransformedToCustomer(false);
			$lead->setState(
				$this->entityManager->getRepository(LeadState::class)->findOneBy(['code' => 'new'])
			);
			$lead->setAddress('Alger');
			$this->entityManager->persist($lead);
			
		
			return $lead;
		}
		private function processTypeOne(Lead $lead)
		{
			$lead->setEmployee($this->entityManager->getRepository(Employee::class)->findOneById(rand(4, 9)));
			$lead->setState(
				$this->entityManager->getRepository(LeadState::class)->findOneBy(['code' => 'AFF'])
			);
			$lastInteraction = $this->addRandomdays($lead->getDateAdd());
			$lead->setLastInteraction($lastInteraction);
			$this->CreateInteraction('AFF',$lead,$lastInteraction);
			
		}
		
		private function processTypeTwo(Lead $lead)
		{
			$employee = $this->entityManager->getRepository(Employee::class)->findOneById(rand(4, 9));
				//dd($employee);
			$lead->setEmployee($employee);
			$lead->setState(
				$this->entityManager->getRepository(LeadState::class)->findOneBy(['code' => 'AFF'])
			);
			$lastInteraction = $this->addRandomdays($lead->getDateAdd());
			$lead->setLastInteraction($lastInteraction);
			$date = $lastInteraction;
			$date= $this->CreateInteraction('AFF',$lead,$date);
			$date= $this->CreateInteraction('QAL',$lead,$date);
			$date=$this->CreateInteraction('GAG',$lead,$date);
			$customer = $this->CreateCustomer($lead,$date);
			
			return $customer;
		}
		
		private function processTypeThree(Lead $lead)
		{
			$lead->setEmployee($this->entityManager->getRepository(Employee::class)->findOneById(rand(4, 9)));
			$lead->setState(
				$this->entityManager->getRepository(LeadState::class)->findOneBy(['code' => 'AFF'])
			);
			$lastInteraction = $this->addRandomdays($lead->getDateAdd());
			$lead->setLastInteraction($lastInteraction);
			$interactionHistory = new InteractionHistory();
			$interactionHistory->setDateAdd($lastInteraction);
			$interactionHistory->setLead($lead);
			$interactionHistory->setEmployee($lead->getEmployee());
			$interactionHistory->setIsDeleted(false);
			$interactionHistory->setType(
				$this->entityManager->getRepository(InteractionType::class)->findOneByCode('AUT')
			);
			$interactionHistory->setSource(
				$this->entityManager->getRepository(InteractionSource::class)->findOneByCode('SYS')
			);
			$interactionHistory->setText('Affected');
			$interactionHistory->setLeadState($lead->getState());
			$this->entityManager->persist($interactionHistory);
			$this->entityManager->flush();
			$lead->setState(
				$this->entityManager->getRepository(LeadState::class)->findOneBy(['code' => 'QAL'])
			);
			$lastInteraction = $this->addRandomdays($lead->getDateAdd());
			$lead->setLastInteraction($lastInteraction);
			$interactionHistory = new InteractionHistory();
			$interactionHistory->setDateAdd($lastInteraction);
			$interactionHistory->setLead($lead);
			$interactionHistory->setEmployee($lead->getEmployee());
			$interactionHistory->setIsDeleted(false);
			$interactionHistory->setType(
				$this->entityManager->getRepository(InteractionType::class)->findOneById(rand(1, 5))
			);
			$interactionHistory->setSource(
				$this->entityManager->getRepository(InteractionSource::class)->findOneById(rand(1, 5))
			);
			$interactionHistory->setText('Qualified');
			$interactionHistory->setLeadState($lead->getState());
			$this->entityManager->persist($interactionHistory);
			$this->entityManager->persist($lead);
		}
		
		private function CreateCustomer(Lead $lead,\DateTime $lastInteraction): Customer
		{
			$customer = new Customer();
			$uuid = new UuidV4();
			$customer->setUuid($uuid);
			$customer->setDateAdd($lastInteraction);
			$customer->setFirstName($lead->getFirstName());
			$customer->setLastName($lead->getLastName());
			$customer->setPhone($lead->getPhone1());
			$customer->setEmail($lead->getEmail1());
			$customer->setIsDeleted(false);
			$customer->setEmployee($lead->getEmployee());
			$customer->setLead($lead);
			$customer->setDateUpdated($lastInteraction);
			$customer->setIsDeleted(false);
			$customer->setState(
				$this->entityManager->getRepository(CustomerState::class)->findOneBy(['code' => 'CRS'])
			);
			$customer->setIsActive(false);
			$customer->setAddress($lead->getAddress());
			$customer->addFormatInterest(
				$this->entityManager->getRepository(CFormatBien::class)->find(rand(1, 14))
			);
			$this->entityManager->persist($customer);
			return $customer;
		}
		
		private function CreateInteraction($type,$lead,$date): \DateTime
		{
			switch ($type) {
			
				case 'QAL':
					$state= $this->entityManager->getRepository(LeadState::class)->findOneBy(['code' => 'QAL']);
					$type = $this->entityManager->getRepository(InteractionType::class)->findOneById(rand(1, 5));
					$text = 'Qualified';
					break;
				case 'GAG':
					$state= $this->entityManager->getRepository(LeadState::class)->findOneBy(['code' => 'GAG']);
					$type = $this->entityManager->getRepository(InteractionType::class)->findOneById(rand(1, 5));
					$text = 'Gagné';
					break;
				case 'AFF':
					$state= $this->entityManager->getRepository(LeadState::class)->findOneBy(['code' => 'AFF']);
					$type = $this->entityManager->getRepository(InteractionType::class)->findOneById(rand(1, 5));
					$text = 'Affecté';
					break;
				default:
					$state= $this->entityManager->getRepository(LeadState::class)->findOneBy(['code' => 'QAL']);
					$type = $this->entityManager->getRepository(InteractionType::class)->findOneById(rand(1, 5));
					$text = 'Qualified';
			}
			$interactionHistory = new InteractionHistory();
			$newDate = $this->addRandomdays($date);
			$interactionHistory->setDateAdd($newDate);
			$interactionHistory->setEmployee($this->entityManager->getRepository(Employee::class)->findOneById(rand(4, 9)));
			$interactionHistory->setIsDeleted(false);
			$interactionHistory->setType(
			$type
			);
			$interactionHistory->setSource(
				$this->entityManager->getRepository(InteractionSource::class)->findOneById(rand(1, 5))
			);
			$interactionHistory->setText($text);
			$interactionHistory->setLeadState(
				$state
			);
			$lead->setLastInteraction($interactionHistory->getDateAdd());
			$this->entityManager->persist($interactionHistory);
			$this->entityManager->persist($lead);
			return $newDate;
		}
		
	}