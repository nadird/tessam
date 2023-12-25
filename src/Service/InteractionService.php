<?php
	namespace App\Service;
	use App\Entity\Customer;
	use App\Entity\Employee;
	use App\Entity\InteractionHistory;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\Security\Core\User\UserInterface;
	use Symfony\Contracts\Translation\TranslatorInterface;
	use Psr\Log\LoggerInterface;
	
	class InteractionService {
		private EntityManagerInterface $entityManager;
		private TranslatorInterface $translator;
		private LoggerInterface $logger;
		
		public function __construct(
			EntityManagerInterface $entityManager,
			TranslatorInterface $translator,
			LoggerInterface $logger
		) {
			$this->entityManager = $entityManager;
			$this->translator = $translator;
			$this->logger = $logger;
		}
		
		public function addInteraction(Customer $customer, InteractionHistory $interaction,$user): void {
			$interaction->setEmployee($user);
			$interaction->setCustomer($customer);
			$interaction->setDateAdd(new \DateTime());
			$interaction->setIsDeleted(false);
			
			$this->entityManager->persist($interaction);
			
			$customer->setDateUpdated(new \DateTime());
			$customer->setState($interaction->getCustomerState());
			
			$this->entityManager->flush();
			
			// Logging
			$this->logger->info('New interaction added by ' . $interaction->getEmployee()->getFirstname() . ' ' . $interaction->getEmployee()->getLastname() . ' for customer ' . $customer->getFirstName() . ' ' . $customer->getLastName());
		}
		
		public function addInteractionNewOrder(Customer $customer,Employee $employee): void {
			$interactionNewOrder = new InteractionHistory();
			$interactionNewOrder->setCustomer($customer);
			$interactionNewOrder->setEmployee($employee);
			$interactionNewOrder->setDateAdd(new \DateTime());
			$interactionNewOrder->setIsDeleted(false);
			$interactionNewOrder->setCustomerState(
				$this->entityManager->getRepository(\App\Entity\CustomerState::class)->findOneByCode("PRO")
			);
			$interactionNewOrder->setType(
				$this->entityManager->getRepository(\App\Entity\InteractionType::class)->findOneByCode("AUT")
			);
			$interactionNewOrder->setSource(
				$this->entityManager->getRepository(\App\Entity\InteractionSource::class)->findOneByCode("SYS")
			);
			$interactionNewOrder->setText("Nouvelle commande de ".$customer->getFirstName()." ".$customer->getLastName()." Par ".$employee->getFirstname()." ".$employee->getLastname());
			$this->entityManager->persist($interactionNewOrder);
		}
		public function getSuccessMessage(): string {
			return $this->translator->trans('app.dashboard.crm.customer.interaction.added_successfully');
		}
	}
