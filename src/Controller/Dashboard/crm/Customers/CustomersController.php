<?php
	namespace App\Controller\Dashboard\crm\Customers;
	
	
	
	
	
	use App\Entity\BankCredit;
	use App\Entity\Customer;
	use App\Entity\Document;
	use App\Entity\Employee;
	use App\Entity\InteractionHistory;
	use App\Entity\Notification;
	use App\Entity\Order;
	use App\Entity\Reduction;
	use App\Form\Dashboard\crm\customers\CustomerAccountType;
	use App\Form\Dashboard\crm\customers\CustomerFormType;
	use App\Form\Dashboard\crm\customers\CustomerInteractionType;
	use App\Form\Dashboard\crm\customers\CustomerInterestType;
	use App\Form\Dashboard\crm\customers\OrderCustomerType;
	use App\Repository\CustomerRepository;
	use App\Repository\InteractionHistoryRepository;
	use App\Service\DocumentUploadService;
	use App\Service\InteractionService;
	use App\Service\NotificationService;
	use App\Service\TodoListTemplateAffectation;
	use Doctrine\ORM\EntityManagerInterface;
	use Knp\Component\Pager\PaginatorInterface;
	use Psr\Log\LoggerInterface;
	use Symfony\Bridge\Doctrine\Form\Type\EntityType;
	use Symfony\Bridge\Doctrine\Types\UuidType;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\RequestStack;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
	use Symfony\Component\Routing\Annotation\Route;
	use Symfony\Component\Uid\Uuid;
	use Symfony\Contracts\Translation\TranslatorInterface;
	
	#[Route('/dashboard/crm/customers', name: 'app_dashboard_crm_customer_')]
	class CustomersController extends AbstractController
	{
		public function __construct(
			private EntityManagerInterface $entityManager,
			private CustomerRepository $customerRepository,
			private RequestStack $requestStack,
			private DocumentUploadService $documentUploadService,
			private TodoListTemplateAffectation $todoListTemplateAffectation,
			private PaginatorInterface $paginator,
			private TranslatorInterface $translator,
			private LoggerInterface $logger,
			private NotificationService $notificationService,
			private InteractionService $interactionService,
			private UserPasswordHasherInterface $passwordEncoder,
		
		){
		}
		#[Route('/', name: 'index')]
		public function index():Response
		{
			$pagination = $this->paginator->paginate(
				$this->customerRepository->findByIsDeleted(false),
				$this->requestStack->getCurrentRequest()->query->getInt('page', 1),
				50
			);
			
			
			
			return $this->render('dashboard/crm/customer/index.html.twig', [
				'controller_name' => 'Sales',
				'customers' => $pagination,
				'parentController'=>'Customers Index',
				'pageSubTitle' => 'All Customers',
			]);
		}
		#[Route('/page/{id}', name: 'customer_page')]
		public function page($id):Response
		{
			$customer = $this->customerRepository->findOneById($id);
			$fromNotif = $this->requestStack->getCurrentRequest()->query->get('notification');
			
			if($fromNotif){
				$this->notificationService->processNotification(intval($fromNotif));
			}
			$interactions = $this->interactRepository->findByCustomerNotDeleted($customer);
			$leadInteractions = $this->interactRepository->findByLeadNotDeleted($customer->getLead());
			$Interactionform = $this->createForm(CustomerInteractionType::class, $newInteraction = new InteractionHistory());
			$Interactionform->handleRequest($this->requestStack->getCurrentRequest());
			if($Interactionform->isSubmitted() && $Interactionform->isValid()){
				$this->interactionService->addInteraction($customer, $newInteraction, $this->getUser());
				$this->addFlash('success', $this->interactionService->getSuccessMessage());
				
				return $this->redirectToRoute('app_dashboard_crm_customer_customer_page', ['id' => $customer->getId()]);
			}
			
			$documentForm = $this->createForm(\App\Form\Dashboard\utils\DocumentType::class);
			$lead = $customer->getLead();
			$documentForm = $this->createCustomerDocumentForm();
			$documentForm->handleRequest($this->requestStack->getCurrentRequest());
			if($documentForm->isSubmitted() && $documentForm->isValid()){
				$document = new Document();
				$uploadedFile = $documentForm['url']->getData();
				$employee = $this->entityManager->getRepository(Employee::class)->findOneById($this->getUser()->getId());
				$this->documentUploadService->uploadDocumentCustomerForm(
					$document,
					$uploadedFile,
					$customer,
					$lead,
					$this->getParameter('documents_directory'),
					$employee
				);
				$document->setName($documentForm['name']->getData());
				$document->setType($documentForm['type']->getData());
				$document->setCustomer($customer);
				$document->setLead($lead);
				$document->setEmployee($employee);
				$document->setDateAdd(new \DateTime());
				$document->setIsDeleted(false);
				$this->entityManager->persist($document);
				$this->entityManager->flush();
				$this->addFlash('success',
					$this->translator->trans('app.dashboard.crm.customer.document.added_successfully')
				);
				$this->logger->info('New document added by '.$this->getUser()->getFirstname().' '.$this->getUser()->getLastname().' for customer '.$customer->getFirstName().' '.$customer->getLastName());
				return $this->redirectToRoute('app_dashboard_crm_customer_customer_page', ['id'=>$customer->getId()]);
			}
			
			$documents = $this->entityManager->getRepository(Document::class)->findCustomerNotDeleted($customer->getId());
			if($customer->getTodoLists()->isEmpty()){
				$this->todoListTemplateAffectation->newCustomerTodoList($this->getUser(),$customer);
			}
			$todoList= $customer->getTodoLists();
			
			$interestForm = $this->createForm(CustomerInterestType::class);
			$interestForm->handleRequest($this->requestStack->getCurrentRequest());
			if($interestForm->isSubmitted() && $interestForm->isValid()){
				$interest = $interestForm->getData();
				$customer->addFormatInterest($interest['format']);
				$this->entityManager->persist($customer);
				$this->entityManager->flush();
				$this->addFlash('success',
					$this->translator->trans('app.dashboard.crm.customer.interest.added_successfully')
				);
				$this->logger->info('New interest added by '.$this->getUser()->getFirstname().' '.$this->getUser()->getLastname().' for customer '.$customer->getFirstName().' '.$customer->getLastName());
				return $this->redirectToRoute('app_dashboard_crm_customer_customer_page', ['id'=>$customer->getId()]);
			}
			$order = new Order();
			$orderForm = $this->createForm(OrderCustomerType::class, $order);
			$orderForm->handleRequest($this->requestStack->getCurrentRequest());
			if($orderForm->isSubmitted() && $orderForm->isValid()){
				$reduction = $orderForm['reduction']->getData();
				if($reduction) {
					if($reduction>0){
					$newreduction= new Reduction();
					$newreduction->setTotal($reduction);
					$newreduction->setDateAdd(new \DateTime());
					$newreduction->setEmployee($this->getUser());
					$newreduction->setIsApproved(false);
					$newreduction->setName('Reduction pour '.$customer->getFirstName().' '.$customer->getLastName());
					$newreduction->setNote('Reduction '.$reduction.' Da. Pour '.$customer->getFirstName().' '.$customer->getLastName().' Par '.$this->getUser()->getFirstname().' '.$this->getUser()->getLastname());
					$order->setReduction($newreduction);
					$this->entityManager->persist($newreduction);
					$this->addFlash('success','Réduction prise en charge');
					$this->logger->info('Reduction',['customer'=>$customer->getFirstName().' '.$customer->getLastName(),'reduction'=>$reduction,'employee'=>$this->getUser()->getFirstname().' '.$this->getUser()->getLastname()]);
					}
				}
				$bien = $orderForm['bien']->getData();
				$type = $orderForm['orderType']->getData();
				$credit = $orderForm['credit']->getData();
				if($credit){
					$bankCredit = new BankCredit();
					$bankCredit->setBank($orderForm['bank']->getData());
					$bankCredit->setTotal($credit);
					$bankCredit->setState(
						$this->entityManager->getRepository(\App\Entity\BankCreditState::class)->findOneByCode("WAIT")
					);
					$bankCredit->setIsValid(false);
					$bankCredit->setDateAdd(new \DateTime());
					$bankCredit->setOrderCredit($order);
					$this->entityManager->persist($bankCredit);
					$this->addFlash('success','Crédit bancaire pris en charge');
					$this->logger->info('Crédit bancaire',['customer'=>$customer->getFirstName().' '.$customer->getLastName(),'credit'=>$credit,'employee'=>$this->getUser()->getFirstname().' '.$this->getUser()->getLastname()]);
					$bankCreditStateHistory = new \App\Entity\BankCreditStateHistory();
					$bankCreditStateHistory->setCredit($bankCredit);
					$bankCreditStateHistory->setEmployee($this->getUser());
					$bankCreditStateHistory->setDateAdd(new \DateTime());
					$bankCreditStateHistory->setState(
						$this->entityManager->getRepository(\App\Entity\BankCreditState::class)->findOneByCode("WAIT")
					);
					$bankCreditStateHistory->setText("Damande de crédit bancaire pris en charge par ".$this->getUser()->getFirstname()." ".$this->getUser()->getLastname());
					$this->entityManager->persist($bankCreditStateHistory);
					
				}
				
				$order->setOrderType($type);
				$uuid = Uuid::v5(Uuid::v4(), $customer->getId().$bien->getId().$type->getId());
				$order->setUuid($uuid);
				$bien->setIsHold(true);
				$order->setCustomer($customer);
				$order->setEmployee($this->getUser());
				$order->setDateAdd(new \DateTime());
				$order->setIsDeleted(false);
				$order->setIsValid(false);
				$order->setIsCompleted(false);
				$order->setDateUpdated(new \DateTime());
				$order->setBien($bien);
				$order->setNotaireFees($bien->getFinalPrice()*0.02);
				$order->setBienPrice($bien->getFinalPrice());
				$order->setTotalHt($orderForm['finalPrice']->getData());
				$order->setOrderState(
					$this->entityManager->getRepository(\App\Entity\OrderState::class)->findOneByCode("WAIT")
				);
				$order->setCoProprityFees($bien->getProject()->getCoProprityFees());
				
				$this->entityManager->persist($order);
				$this->entityManager->flush();
				$this->interactionService->addInteractionNewOrder($customer,$order->getEmployee());
				$bien->setState(
					$this->entityManager->getRepository(\App\Entity\CbienState::class)->findOneByCode("RES")
				);
				$bienStateHistory = new \App\Entity\CbienStateHistory();
				$bienStateHistory->setBien($bien);
				$bienStateHistory->setEmployee($this->getUser());
				$bienStateHistory->setText("Réservé par ".$customer->getFirstName()." ".$customer->getLastName()." Par ".$this->getUser()->getFirstname()." ".$this->getUser()->getLastname());
				$bienStateHistory->setDateAdd(new \DateTime());
				$bienStateHistory->setState(
					$this->entityManager->getRepository(\App\Entity\CbienState::class)->findOneByCode("RES")
				);
				$this->entityManager->persist($bienStateHistory);
				$this->entityManager->persist($bien);
				$customer->setDateUpdated(new \DateTime());
				$customer->setState(
					$this->entityManager->getRepository(\App\Entity\CustomerState::class)->findOneByCode("PRO")
				);
				$this->entityManager->flush();
				
				$this->addFlash('success',
					$this->translator->trans('app.dashboard.crm.customer.order.added_successfully')
				);
				$this->logger->info('New order added by '.$this->getUser()->getFirstname().' '.$this->getUser()->getLastname().' for customer '.$customer->getFirstName().' '.$customer->getLastName());
				return $this->redirectToRoute('app_dashboard_crm_customer_customer_page', ['id'=>$customer->getId()]);
			}
			
			$this->entityManager->flush();
			$customerstats = $this->customerStats($customer->getOrders(),$interactions,$documents,$customer);
			$orders = $this->entityManager->getRepository(Order::class)->findByCustomerNotDeleted($customer);
			$value = $this->valueCustomer($customer);
			
			$controller_name = $this->translator->trans('app.dashboard.crm.customer.controller_name').': '.$customer->getFirstName().' '.$customer->getLastName();
			$parent_controller_name=$this->translator->trans('app.dashboard.crm.customer.sales_controller_name');
			return $this->render('dashboard/crm/customer/customer_page.html.twig', [
				'controller_name' => $controller_name,
				'parentController'=>$parent_controller_name,
				'pageSubTitle' => 'Customer main page',
				'customer' => $customer,
				'interactions' => $interactions,
				'interactionForm'=>$Interactionform->createView(),
				'todoLists'=>$todoList,
				'documentForm'=>$documentForm->createView(),
				'documents'=>$documents,
				'lead'=>$lead,
				'interestForm'=>$interestForm->createView(),
				'orderForm'=>$orderForm->createView(),
				'orders'=>$orders,
				'value'=>$value,
				'customerstats'=>$customerstats,
				'employees'=>$this->entityManager->getRepository(\App\Entity\Employee::class)->findAll(),
				
			]);
		}
		#[Route('/changeEmployee', name: 'customer_change_employee')]
		public function changeEmployee():Response
		{
			$referer = $this->requestStack->getCurrentRequest()->headers->get('referer');
			$customer = $this->customerRepository->findOneById(intval($this->requestStack->getCurrentRequest()->request->get('customer')));
			$employee = $this->entityManager->getRepository(\App\Entity\Employee::class)->findOneById(intval($this->requestStack->getCurrentRequest()->request->get('employee')));
			$customer->setEmployee($employee);
			$interaction = new InteractionHistory();
			$interaction->setCustomer($customer);
			$interaction->setEmployee($this->getUser());
			$interaction->setDateAdd(new \DateTime());
			$interaction->setIsDeleted(false);
			$interaction->setCustomerState($customer->getState());
			$interaction->setType(
				$this->entityManager->getRepository(\App\Entity\InteractionType::class)->findOneByCode("AUT")
			);
			$interaction->setSource(
				$this->entityManager->getRepository(\App\Entity\InteractionSource::class)->findOneByCode("SYS")
			);
			$interaction->setText("Client réaffecté a ".$employee->getFirstname()." ".$employee->getLastname()." Par ".$this->getUser()->getFirstname()." ".$this->getUser()->getLastname());
			$customer->setDateUpdated(new \DateTime());
			$this->entityManager->persist($interaction);
			$this->entityManager->persist($customer);
			$this->entityManager->flush();
			
			$this->addFlash('success',
				$this->translator->trans('app.dashboard.crm.customer.employee_changed_successfully'). ' '.
				$employee->getFirstname().' '.$employee->getLastname().
				'Action performed by '.$this->getUser()->getFirstname().' '.$this->getUser()->getLastname()
			);
			$this->logger->info('Customer '.$customer->getFirstName().' '.$customer->getLastName().' has been changed to '.$employee->getFirstname().' '.$employee->getLastname().' by '.$this->getUser()->getFirstname().' '.$this->getUser()->getLastname());
			
			return $this->redirect($referer);
			
		}
		
		#[Route('/customer/update/{id}', name: 'customer_update')]
		public function update(Customer $customer):Response
		{
			$referer = $this->requestStack->getCurrentRequest()->headers->get('referer');
			$form = $this->createForm( CustomerFormType::class, $customer);
			$form->handleRequest($this->requestStack->getCurrentRequest());
			if($form->isSubmitted()&&$form->isValid()){
				$customer->setDateUpdated(new \DateTime());
				$this->entityManager->persist($customer);
				$this->entityManager->flush();
				$this->addFlash('success','Customer updated successfully');
				$this->logger->info('Customer '.$customer->getFirstName().' '.$customer->getLastName().' has been updated by '.$this->getUser()->getFirstname().' '.$this->getUser()->getLastname());
				return $this->redirect($referer);
			}
			return $this->render('dashboard/crm/customer/update.html.twig', [
				'controller_name' => 'Modifier'.': '.$customer->getFirstName().' '.$customer->getLastName(),
				'parentController'=>'Customers',
				'pageSubTitle' => 'Update Customer',
				'form'=>$form->createView(),
				'customer'=>$customer,
			]);
			
		}
		
		
		private function valueCustomer($customer){
			$value = 0;
			$orders = $customer->getOrders();
			$interests = $customer->getFormatInterest();
			//dd($interests);
			foreach($orders as $order){
				$value += $order->getTotalHt();
			}
			if(count($interests)==0){
			
				$interests = $customer->getLead()->getFormatBienInterest();
				
			}
			if($value == 0 && count($interests) > 0){
				$tmpValue = 0;
				$i= 0;
				foreach($interests as $interest){
					$tmpValue += $interest->getPrice();
					$i++;
				}
				$value= round( $tmpValue/$i);
			}
			return $value;
		}
		
		
		
		public function stateStats($customers){
			
			$stats = [];
			foreach ($customers as $customer){
				if(isset($stats[$customer->getState()->getName()])){
					$stats[$customer->getState()->getName()]['number'] = $stats[$customer->getState()->getName()]['number'] +1;
				}else{
					$stats[$customer->getState()->getName()]['number'] = 1;
					$stats[$customer->getState()->getName()]['state'] =$customer->getState() ;
				}
			}
			return $stats;
		}
		
		public function customerStats($orders,$interactions,$documents,$customer):array
		{
			$customerstats= [];
			$customerstats['nbrOrders'] = count($orders);
			$customerstats['nbrInteractions'] = count($interactions);
			$customerstats['nbrDocuments'] = count($documents);
			$customerstats['totalPaiements'] = 0;
			$customerstats['totalAmountOrders'] = 0;
			
			foreach($orders as $order){
				$customerstats['totalAmountOrders'] += $order->getTotalHt();
			}
			$paiements = $customer->getPaiementHistories();
			foreach($paiements as $paiement){
				//dd($paiement);
				if($paiement->IsIsValid() == true){
					$customerstats['totalPaiements'] += $paiement->getTotal();
				}
			}
			return $customerstats;
		}
		public function createCustomerDocumentForm()
		{
			$documentForm = $this->createFormBuilder();
			$documentForm->add('url', \Symfony\Component\Form\Extension\Core\Type\FileType::class, [
				'label' => 'Document',
				'mapped' => false,
				'required' => true,
				'constraints' => [
					new \Symfony\Component\Validator\Constraints\File([
						'maxSize' => '4096k',
						'mimeTypesMessage' => 'Please upload a valid PDF document',
					])
				],
			]);
			$documentForm->add('name', \Symfony\Component\Form\Extension\Core\Type\TextType::class, [
				'label' => 'Document name',
				'mapped' => false,
				'required' => true,
			]);
			$documentForm->add('type', \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class, [
				'label' => 'Document type',
				'mapped' => false,
				'required' => true,
				'choices' => $this->entityManager->getRepository(\App\Entity\DocumentType::class)->findByIsCustomer(true),
				'choice_label' => 'name',
			]);
			$documentForm->add('submit', \Symfony\Component\Form\Extension\Core\Type\SubmitType::class, [
				'label' => 'Add document',
			]);
			$documentForm = $documentForm->getForm();
			return $documentForm;
		}
		#[Route('/account/create/{id}', name: 'customer_account_create')]
		public function createCustomerAccount(Customer $customer):Response
		{
			$referer = $this->requestStack->getCurrentRequest()->headers->get('referer');
			$customerForm = $this->createForm(CustomerAccountType::class, $customer);
			$customerForm->handleRequest($this->requestStack->getCurrentRequest());
			if($customerForm->isSubmitted() && $customerForm->isValid()){
				$customer->setPassword(
					$this->passwordEncoder->hashPassword(
						$customer,
						$customerForm->get('password')->getData()
					)
				);
				$customer->setDateUpdated(new \DateTime());
				$customer->setIsActive(true);
				$customer->setRoles(['ROLE_CUSTOMER','ROLE_USER']);
				$this->entityManager->persist($customer);
				$this->entityManager->flush();
				$this->addFlash('success','Customer updated successfully');
				$this->logger->info('Customer '.$customer->getFirstName().' '.$customer->getLastName().' has been updated by '.$this->getUser()->getFirstname().' '.$this->getUser()->getLastname());
				return $this->redirect($referer);
			}
			return $this->render('dashboard/crm/customer/new_customer_account.html.twig', [
				'controller_name' => 'Modifier'.': '.$customer->getFirstName().' '.$customer->getLastName(),
				'parentController'=>'Customers',
				'pageSubTitle' => 'Update Customer',
				'form'=>$customerForm->createView(),
				'customer'=>$customer,
			]);
		}
		
	}