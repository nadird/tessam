<?php
	namespace App\Service;
	
	
	
	
	use App\Entity\BienAdditional;
	use App\Entity\BienAdditionalState;
	use App\Entity\Employee;
	use App\Entity\InteractionHistory;
	use App\Entity\InteractionSource;
	use App\Entity\InteractionType;
	use App\Entity\Notification;
	use App\Entity\OrderDetails;
	use Doctrine\ORM\EntityManagerInterface;
	use Doctrine\ORM\EntityRepository;
	use Doctrine\ORM\QueryBuilder;
	use Psr\Log\LoggerInterface;
	use Symfony\Bridge\Doctrine\Form\Type\EntityType;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\Form\Extension\Core\Type\FormType;
	use Symfony\Component\Form\FormBuilder;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\HttpFoundation\RequestStack;
	use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
	use Symfony\Component\Uid\UuidV4;
	
	class OrderService extends AbstractController
	{
		public function __construct(
			private EntityManagerInterface $entityManager,
			private LoggerInterface $logger,
			private RequestStack $requestStack,
		){
		}
		
		
		public function AddAutomaticInteractionFlashAndLog(
			$order,
			?Employee $employee = null,
			?string $interactionType = null,
			?string $interactionMessage = null,
		)
		{
			$interaction = new InteractionHistory();
			$interaction->setCustomer($order->getCustomer());
			$interaction->setDateAdd(new \DateTime());
			$interaction->setEmployee($employee);
			$interaction->setText($interactionMessage);
			$interaction->setType(
				$this->entityManager->getRepository(InteractionType::class)->findOneBy(['code' => 'AUT'])
			);
			$interaction->setSource(
				$this->entityManager->getRepository(InteractionSource::class)->findOneBy(['code' => 'SYS'])
			);
			$interaction->setIsDeleted(false);
			$interaction->setCustomerState($order->getCustomer()->getState());
			$this->entityManager->persist($interaction);
			$this->entityManager->flush();
			$this->logger->info('InteractionHistory created', ['id' => $interaction->getId(),'message'=>$interactionMessage,'type'=>$interactionType,'employee'=>$employee,'order'=>$order,'customer'=>$order->getCustomer()]);
			$this->requestStack->getCurrentRequest()->getSession()->getFlashBag()->add('success', $interactionMessage);
		}
		
		public function processNotification($fromNotif)
		{
			if($fromNotif){
				$notification = $this->entityManager->getRepository(Notification::class)->findOneById(intval($fromNotif));
				$notification->setIsProcessed(true);
				$this->entityManager->persist($notification);
				$this->entityManager->flush();
			}
		}
		public function handleNewInteractionForm($newInteraction,$customer){
			$newInteraction->setEmployee($this->getUser());
			$newInteraction->setCustomer($customer);
			$newInteraction->setCustomerState($customer->getState());
			$newInteraction->setDateAdd(new \DateTime());
			$newInteraction->setIsDeleted(false);
			$this->entityManager->persist($newInteraction);
			$customer->setDateUpdated(new \DateTime());
			$customer->setState($newInteraction->getCustomerState());
			$this->entityManager->flush();
			return $newInteraction;
		}
		public function handlePaiementForm($newpaiement,$order)
		{
			$newpaiement->setEmployee($this->getUser());
			$newpaiement->setIsValid(false);
			$newpaiement->setCustomer($order->getCustomer());
			$newpaiement->setDateAdd(new \DateTime());
			$newpaiement->setIsDeleted(false);
			$newpaiement->setOrderPaiement($order);
			$newpaiement->setIsReservationPaiement(false);
			$uuId= new UuidV4();
			$newpaiement->setUid($uuId);
			$this->entityManager->persist($newpaiement);
			$order->getCustomer()->setDateUpdated(new \DateTime());
			$this->entityManager->flush();
			return $newpaiement;
		}
		public function handleNewDocumentForm($document,$customer,$newFilename)
		{
			$document->setUrl($newFilename);
			$document->setEmployee($this->getUser());
			$document->setCustomer($customer);
			$document->setDateAdd(new \DateTime());
			$document->setIsDeleted(false);
			$this->entityManager->persist($document);
			$this->entityManager->flush();
			return $document;
		}
		public function handleOrderAdditionalProductForm($data,$order)
		{
			$newOrderdetails = new OrderDetails();
			$newOrderdetails->setDateAdd(new \DateTime());
			$newOrderdetails->setIsDeleted(false);
			$newOrderdetails->setOrderOrderDetail($order);
			$newOrderdetails->setAdditionalProduct($data['additionalProduct']);
			$newOrderdetails->setPrice($data['price']);
			$newOrderdetails->setName($data['additionalProduct']->getName());
			$newOrderdetails->setNote($data['note']);
			$this->entityManager->persist($newOrderdetails);
			$this->entityManager->flush();
			return $newOrderdetails;
			
		}
		public function creatAdditionalBienForm($order){
			$additionalBiensForm = $this->createFormBuilder();
			$additionalBiensForm->add('additional',EntityType::class,[
				'class' => BienAdditional::class,
				'query_builder' => function (EntityRepository $er) use ($order): QueryBuilder {
					return $er->createQueryBuilder('b')
						->andWhere('b.state = 1')
						->andWhere('b.project =:val')
						->setParameter('val', $order->getBien()->getProject());
				},
				'choice_label' => 'name',
				'multiple' => false,
				'expanded' => false,
				'mapped' => false,
				'autocomplete' => 'true',
			
			]);
			$additionalBiensForm = $additionalBiensForm->getForm();
			return $additionalBiensForm;
		}
		public function handleAdditionalBiensForm($additional,$order)
		{
			$additional->setState(
				$this->entityManager->getRepository(BienAdditionalState::class)->findOneBy(['code'=>'RES'])
			);
			$order->addBienAdditional($additional);
			$order->setDateUpdated(new \DateTime());
			$this->entityManager->persist($order);
			$this->entityManager->persist($additional);
			$this->entityManager->flush();
			return $additional;
			
		}
		
	}