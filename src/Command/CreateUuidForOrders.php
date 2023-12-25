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
	use Symfony\Component\Uid\Uuid;
	use function PHPUnit\Framework\isInstanceOf;
	use function PHPUnit\Framework\isType;

// the name of the command is what users type after "php bin/console"
	#[AsCommand(name: 'app:createUuid', description: 'Arrange Areas ',)]
	class CreateUuidForOrders extends Command
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
			$orders = $this->entityManager->getRepository(\App\Entity\Order::class)->findAll();
			foreach ($orders as $order){
				$uuid = Uuid::v5(Uuid::v4(), $order->getCustomer()->getId().$order->getBien()->getId().$order->getBien()->getType()->getId());
				$order->setUuid($uuid);
				$this->entityManager->persist($order);
			}
			$this->entityManager->flush();
			return Command::SUCCESS;
			
		}
		
		
		
		
		
		
		
		
		
		
		
		
	}