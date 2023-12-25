<?php
	
	namespace App\Service;
	use App\Entity\Configuration;
	use App\Entity\Todo;
	use App\Entity\TodoList;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\Mime\Email;
	
	class TodoListTemplateAffectation
	{
		public function __construct(
			private EntityManagerInterface $entityManager,
		){
		
		}
		
		public function newContactTodoList($user,$conntact){
			$todolist = new TodoList();
			$todolist->setEmployee($user);
			$todolist->setContact($conntact);
			$todolist->setDateAdd(new \DateTime());
			$todolist->setIsDeleted(false);
			$todolist->setDateAdd(new \DateTime());
			$todolist->setName('Contact '.$conntact->getFirstname().' '.$conntact->getLastname());
			$todolist->setIsCompleted(false);
			$todolist->setIsTemplate(false);
			$this->entityManager->persist($todolist);
			$this->entityManager->flush();
			$template = $this->entityManager->getRepository(TodoList::class)->findOneById(
				$this->entityManager->getRepository(Configuration::class)->findOneByConfigkey('NEW_CONTACT_TODO_LIST')->getConfigValue()
			);
			
			foreach($template->getTodos() as $templateTodo){
				$todo = new Todo();
				$todo->setIsDone(false);
				$todo->setOrderInList($templateTodo->getOrderInList());
				$todo->setText($templateTodo->getText());
				$todo->setTodoList($todolist);
				$this->entityManager->persist($todo);
			}
			$this->entityManager->flush();
			return $todolist;
		}
		public function newLeadTodoList($user,$lead){
			$todolist = new TodoList();
			$todolist->setEmployee($user);
			$todolist->setLead($lead);
			$todolist->setDateAdd(new \DateTime());
			$todolist->setIsDeleted(false);
			$todolist->setDateAdd(new \DateTime());
			$todolist->setName('New Lead '.$lead->getFirstname().' '.$lead->getLastname());
			$todolist->setIsCompleted(false);
			$todolist->setIsTemplate(false);
			$this->entityManager->persist($todolist);
			$this->entityManager->flush();
			$template = $this->entityManager->getRepository(TodoList::class)->findOneById(
				$this->entityManager->getRepository(Configuration::class)->findOneByConfigkey('NEW_LEAD_TODO_LIST')->getConfigValue()
			);
			
			foreach($template->getTodos() as $templateTodo){
				$todo = new Todo();
				$todo->setIsDone(false);
				$todo->setOrderInList($templateTodo->getOrderInList());
				$todo->setText($templateTodo->getText());
				$todo->setTodoList($todolist);
				$this->entityManager->persist($todo);
			}
			$this->entityManager->flush();
			return $todolist;
		}
		public function newCustomerTodoList($user,$customer){
			$todolist = new TodoList();
			$todolist->setEmployee($user);
			$todolist->setCustomer($customer);
			$todolist->setDateAdd(new \DateTime());
			$todolist->setIsDeleted(false);
			$todolist->setDateAdd(new \DateTime());
			$todolist->setName('New Lead '.$customer->getFirstname().' '.$customer->getLastname());
			$todolist->setIsCompleted(false);
			$todolist->setIsTemplate(false);
			$this->entityManager->persist($todolist);
			$this->entityManager->flush();
			$template = $this->entityManager->getRepository(TodoList::class)->findOneById(
				$this->entityManager->getRepository(Configuration::class)->findOneByConfigkey('NEW_CUSTOMER_TODO_LIST')->getConfigValue()
			);
			
			foreach($template->getTodos() as $templateTodo){
				$todo = new Todo();
				$todo->setIsDone(false);
				$todo->setOrderInList($templateTodo->getOrderInList());
				$todo->setText($templateTodo->getText());
				$todo->setTodoList($todolist);
				$this->entityManager->persist($todo);
			}
			$this->entityManager->flush();
			return $todolist;
		}
		public function newOrderTodoList($user,$order){
			$todolist = new TodoList();
			$todolist->setEmployee($user);
			$todolist->setCustomerOrder($order);
			$todolist->setDateAdd(new \DateTime());
			$todolist->setIsDeleted(false);
			$todolist->setDateAdd(new \DateTime());
			$todolist->setName('New Order '.$order->getCustomer()->getFirstname().' '.$order->getCustomer()->getLastname());
			$todolist->setIsCompleted(false);
			$todolist->setIsTemplate(false);
			$this->entityManager->persist($todolist);
			$this->entityManager->flush();
			$template = $this->entityManager->getRepository(TodoList::class)->findOneById(
				$this->entityManager->getRepository(Configuration::class)->findOneByConfigkey('NEW_ORDER_TODO_LIST')->getConfigValue()
			);
			
			foreach($template->getTodos() as $templateTodo){
				$todo = new Todo();
				$todo->setIsDone(false);
				$todo->setOrderInList($templateTodo->getOrderInList());
				$todo->setText($templateTodo->getText());
				$todo->setTodoList($todolist);
				$this->entityManager->persist($todo);
			}
			$this->entityManager->flush();
			return $todolist;
		}
		
	}