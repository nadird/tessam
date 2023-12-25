<?php
	namespace App\Service;
	
	
	use App\Entity\Notification;
	use App\Repository\NotificationRepository;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;
	
	
	
	
	class NotificationService extends AbstractController
	{
		public function __construct(
			private NotificationRepository $notificationRepository,
			private EntityManagerInterface $entityManager,
		){
		}
		public function index():Response
		{
	
			return $this->render('dashboard/index.html.twig', [
			
			
			]);
		}
		public function processNotification(int $fromNotif): void {
			$notification = $this->entityManager->getRepository(Notification::class)->findOneById($fromNotif);
			$notification->setIsProcessed(true);
			$this->entityManager->persist($notification);
			$this->entityManager->flush();
		}
		
		
	}