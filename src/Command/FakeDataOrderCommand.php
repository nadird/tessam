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
	#[AsCommand(name: 'app:fake_orders', description: 'Creates fake Orders ',)]
	class FakeDataOrderCommand extends Command
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
			$Customers= $this->entityManager->getRepository(Customer::class)->findAll();
			$employees= $this->entityManager->getRepository(Employee::class)->findAll();
			$orderTypes= $this->entityManager->getRepository(\App\Entity\OrderType::class)->findAll();
			foreach($Customers as $customer){
				$Cbiens = $this->entityManager->getRepository(\App\Entity\Cbien::class)->findBienNotHoldNotSoldDisponible();
				$selectedBien = $Cbiens[array_rand($Cbiens)];
				$order = new \App\Entity\Order();
				$order->setCustomer($customer);
				$order->setDateAdd($this->randomDay());
				$order->setDateUpdated($this->addRandomdays($order->getDateAdd()));
				$order->setBien($selectedBien);
				$order->setCustomer($customer);
				$order->setEmployee($employees[array_rand($employees)]);
				$order->setCoProprityFees($selectedBien->getProject()->getCoProprityFees());
				$order->setNotaireFees($selectedBien->getFinalPrice()*0.03);
				$order->setTotalHt($selectedBien->getFinalPrice()+$order->getCoProprityFees()+$order->getNotaireFees());
				$order->setUuid(uniqid());
				$order->setBienPrice($selectedBien->getFinalPrice());
				$order->setOrderType($orderTypes[array_rand($orderTypes)]);
				$order->setIsValid(true);
				$order->setIsCompleted(false);
				$order->setOrderState($this->entityManager->getRepository(\App\Entity\OrderState::class)->findOneBy(['code'=>'WAIT']));
				$order->setIsDeleted(false);
				$this->entityManager->persist($order);
				$selectedBien->setIsSold(false);
				$selectedBien->setIsHold(true);
				$selectedBien->setState($this->entityManager->getRepository(\App\Entity\CbienState::class)->findOneBy(['code'=>'RES']));
				$this->entityManager->persist($selectedBien);
				$this->entityManager->flush();
			}
			
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