<?php
	
	namespace App\Controller\Dashboard\utils;
	
	
	use App\Entity\Comment;
	use App\Entity\Notification;
	use App\Entity\NotificationType;
	use App\Repository\AlbumRepository;
	use App\Repository\InteractionHistoryRepository;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\RequestStack;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;
	
	#[Route('/dashboard/utils/document', name: 'dashboard_utils_document_')]
	class DocumentController extends AbstractController
	{
		public function __construct(
			private EntityManagerInterface $entityManager,
			private RequestStack $requestStack,
		){
		}
		
		#[Route('/delete/document/{id}', name: 'delete_document', methods: ['GET', 'POST'])]
		public function index($id): Response
		{
			$referer= $this->requestStack->getCurrentRequest()->headers->get('referer');
			$document = $this->entityManager->getRepository(\App\Entity\Document::class)->findOneById($id);
			$document->setIsDeleted(true);
			$this->entityManager->persist($document);
			$this->entityManager->flush();
			$this->addFlash('success', 'Document deleted successfully');
			return $this->redirect($referer);
			
		}
	}