<?php
	// src/Command/CreateUserCommand.php
	namespace App\Command;
	
	use App\Entity\Cbien;
	use App\Entity\CFormatBien;
	use App\Entity\Commune;
	use App\Entity\Configuration;
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
	use App\Entity\Todo;
	use App\Entity\TodoList;
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
	#[AsCommand(name: 'app:createTodoListsTemplates', description: 'Arrange Areas ',)]
	class CreateTodoListsTemplates extends Command
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
			$config = new Configuration();
			$config->setConfigkey('AFFECTED_LEAD_INTERACTION_TYPE');
			$intreactionType = $this->entityManager->getRepository(InteractionType::class)->findOneBy(['code'=>'AUT']);
			$config->setConfigvalue($intreactionType->getId());
			
			$this->entityManager->persist($config);
			$this->entityManager->flush();
			
			$config = new Configuration();
			$config->setConfigkey('AFFECTED_LEAD_INTERACTION_SOURCE');
			$intreactionSource = $this->entityManager->getRepository(InteractionSource::class)->findOneBy(['code'=>'SYS']);
			$config->setConfigvalue($intreactionSource->getId());
			$this->entityManager->persist($config);
			$this->entityManager->flush();
			
			$config = new Configuration();
			$config->setConfigkey('DEFAULT_CUSTOMER_STATE');
			$customerState = $this->entityManager->getRepository(CustomerState::class)->findOneBy(['code'=>'PRO']);
			$config->setConfigvalue($customerState->getId());
			$this->entityManager->persist($config);
			$this->entityManager->flush();
			
			$config = new Configuration();
			$config->setConfigkey('AFFECTED_LEAD_STATE');
			$leadState = $this->entityManager->getRepository(LeadState::class)->findOneBy(['code'=>'AFF']);
			$config->setConfigvalue($leadState->getId());
			$this->entityManager->persist($config);
			$this->entityManager->flush();
			
			$config = new Configuration();
			$config->setConfigkey('NEW_LEAD_STATE');
			$leadState = $this->entityManager->getRepository(LeadState::class)->findOneBy(['code'=>'NEW']);
			$config->setConfigvalue($leadState->getId());
			$this->entityManager->persist($config);
			$this->entityManager->flush();
			
			
			$config = new Configuration();
			$config->setConfigkey('NEW_LEAD_TODO_LIST');
				$todoList = new TodoList();
				$todoList->setName('New lead');
				$todoList->setIsDeleted(false);
				$todoList->setIsTemplate(true);
				$todoList->setIsCompleted(false);
				$todoList->setDateAdd(new \DateTime());
				$this->entityManager->persist($todoList);
				$this->entityManager->flush();
				$config->setConfigvalue($todoList->getId());
				$this->entityManager->persist($config);
				$this->entityManager->flush();
					
					$todo = new Todo();
					$todo->setText('Qualifier le lead');
					$todo->setOrderInList(1);
					$todo->setIsDone(false);
					$todo->setTodolist($todoList);
					$this->entityManager->persist($todo);
					
					$todo = new Todo();
					$todo->setText('Fixer rendez-vous');
					$todo->setOrderInList(2);
					$todo->setIsDone(false);
					$todo->setTodolist($todoList);
					$this->entityManager->persist($todo);
			$this->entityManager->flush();
			
			$config = new Configuration();
			$config->setConfigkey('NEW_CUSTOMER_TODO_LIST');
			$todoList = new TodoList();
			$todoList->setName('Nouveau client');
			$todoList->setIsDeleted(false);
			$todoList->setIsTemplate(true);
			$todoList->setIsCompleted(false);
			$todoList->setDateAdd(new \DateTime());
			$this->entityManager->persist($todoList);
			$this->entityManager->flush();
			$config->setConfigvalue($todoList->getId());
			
			$this->entityManager->persist($config);
			$this->entityManager->flush();
			
			
				$todo = new Todo();
				$todo->setText('Passer la commande');
				$todo->setOrderInList(1);
				$todo->setIsDone(false);
				$todo->setTodolist($todoList);
				$this->entityManager->persist($todo);
				
				$todo = new Todo();
				$todo->setText('Reserver le bien immobilier');
				$todo->setOrderInList(2);
				$todo->setIsDone(false);
				$todo->setTodolist($todoList);
				$this->entityManager->persist($todo);
				
				$todo = new Todo();
				$todo->setText('Créer un compte utilisateur');
				$todo->setOrderInList(3);
				$todo->setIsDone(false);
				$todo->setTodolist($todoList);
				$this->entityManager->persist($todo);
			
			$this->entityManager->flush();
			
			
			$config = new Configuration();
			$config->setConfigkey('NEW_ORDER_TODO_LIST');
			$todoList = new TodoList();
			$todoList->setName('Nouvele commande');
			$todoList->setIsDeleted(false);
			$todoList->setIsTemplate(true);
			$todoList->setIsCompleted(false);
			$todoList->setDateAdd(new \DateTime());
			$this->entityManager->persist($todoList);
			$this->entityManager->flush();
			$config->setConfigvalue($todoList->getId());
			$this->entityManager->persist($config);
			$this->entityManager->flush();
			
			
			
				$todo = new Todo();
				$todo->setText('Paiement reservation');
				$todo->setOrderInList(1);
				$todo->setIsDone(false);
				$todo->setTodolist($todoList);
				$this->entityManager->persist($todo);
				
				$todo = new Todo();
				$todo->setText('Créer attestation de reservation');
				$todo->setOrderInList(2);
				$todo->setIsDone(false);
				$todo->setTodolist($todoList);
				$this->entityManager->persist($todo);
				
				$todo = new Todo();
				$todo->setText('Créer écheancier');
				$todo->setOrderInList(3);
				$todo->setIsDone(false);
				$todo->setTodolist($todoList);
				$this->entityManager->persist($todo);

			
			$this->entityManager->flush();
			return Command::SUCCESS;
			
		}
		
		
		
		
		
		
		
		
		
		
		
		
	}