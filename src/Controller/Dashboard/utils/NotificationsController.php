<?php
	
	namespace App\Controller\Dashboard\utils;
	
	use App\Repository\NotificationRepository;
	use App\Service\EmailService;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\RequestStack;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;
	
	#[Route('/dashboard/utils/notification', name: 'app_dashboard_utils_notification_')]
	class NotificationsController extends AbstractController
	{
		public function __construct(
			private NotificationRepository $notificationRepository,
			private EntityManagerInterface $entityManager,
			private RequestStack $requestStack
		){
		
		}
		
		#[Route('/', name: 'index')]
		public function index(): Response
		{
			$notifications = $this->notificationRepository->findAllMyNotifications($this->getUser());

			return $this->render('dashboard/index.html.twig', [
				'controller_name' => 'OpsController',
				'notifications' => $notifications
			]);
		}
		#[Route('/mynotifications', name: 'my_notifications')]
		public function myNotifications(): Response
		{
			$notifications = $this->notificationRepository->findLastUnread($this->getUser());
			$notificationsPast = $this->notificationRepository->findLastRed($this->getUser());
			
			return $this->render('dashboard/_partials/_navbar/_parts/horizontal/_notifications.html.twig', [
				'notifications' => $notifications,
				'notificationsPast' => $notificationsPast
			]);
		}
		#[Route('/MyNotificationsPage', name: 'my_notifications_page')]
		public function myNotificationspage(): Response
		{
			$notifications = $this->notificationRepository->findAllMyNotifications($this->getUser());
			
			return $this->render('dashboard/misc/my_notifications_page.html.twig', [
				'notifications' => $notifications,
				'controller_name' => 'My Notifications',
				'pageSubTitle' => 'My Notifications',
				'parentController' => 'Notifications'
				
			]);
		}
		#[Route('/read/{id}', name: 'markread')]
		public function read(int $id): Response
		{
			$referer = $this->requestStack->getCurrentRequest()->headers->get('referer');
	
			$notification = $this->notificationRepository->find($id);
			$notification->setIsProcessed(true);
			$this->entityManager->persist($notification);
			$this->entityManager->flush();
			$this->addFlash('success', 'Notification marked as read');
			return $this->redirect($referer);
		}
		#[Route('/unread/{id}', name: 'markunread')]
		public function unread(int $id): Response
		{
			$referer = $this->requestStack->getCurrentRequest()->headers->get('referer');
			
			$notification = $this->notificationRepository->find($id);
			$notification->setIsProcessed(false);
			$this->entityManager->persist($notification);
			$this->entityManager->flush();
			$this->addFlash('success', 'Notification marked as unread');
			return $this->redirect($referer);
		}
		#[Route('/delete/{id}', name: 'delete')]
		public function delete(int $id): Response
		{
			$referer = $this->requestStack->getCurrentRequest()->headers->get('referer');
			
			$notification = $this->notificationRepository->find($id);
			$notification->setIsDeleted(true);
			$this->entityManager->persist($notification);
			$this->entityManager->flush();
			$this->addFlash('success', 'Notification deleted');
			return $this->redirect($referer);
		}
		#[Route('/deleteall', name: 'deleteall')]
		public function deleteAll(): Response
		{
			$referer = $this->requestStack->getCurrentRequest()->headers->get('referer');
			
			$notifications = $this->notificationRepository->findAllMyNotifications($this->getUser());
			foreach ($notifications as $notification){
				$notification->setIsDeleted(true);
				$this->entityManager->persist($notification);
				$this->entityManager->flush();
			}
			$this->addFlash('success', 'All notifications deleted');
			return $this->redirect($referer);
		}
		#[Route('/readall', name: 'readall')]
		public function redAll(): Response
		{
			$referer = $this->requestStack->getCurrentRequest()->headers->get('referer');
			
			$notifications = $this->notificationRepository->findAllMyNotifications($this->getUser());
			foreach ($notifications as $notification){
				$notification->setIsProcessed(true);
				$this->entityManager->persist($notification);
				$this->entityManager->flush();
			}
			$this->addFlash('success', 'All notifications red');
			return $this->redirect($referer);
		}
		
		
		
	}
