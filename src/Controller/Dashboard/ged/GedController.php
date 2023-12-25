<?php
	namespace App\Controller\Dashboard\ged;
	
	use App\Entity\Document;
	use App\Entity\DocumentType;
	use App\Repository\WebContactRepository;
	use Doctrine\ORM\EntityManagerInterface;
	use Knp\Component\Pager\PaginatorInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\Form\Extension\Core\Type\SearchType;
	use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	use Symfony\Component\HttpFoundation\RequestStack;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
	use Symfony\Component\Routing\Annotation\Route;
	use Symfony\Component\String\Slugger\SluggerInterface;
	
	#[Route('/dashboard/ged', name: 'app_dashboard_ged_')]
	class GedController extends AbstractController
	{
		public function __construct(
			private EntityManagerInterface $entityManager,
			private RequestStack $requestStack,
			private PaginatorInterface $paginator,
		){
		}
		#[Route('/', name: 'index')]
		public function index():Response
		{
			$documentTypes = $this->entityManager->getRepository(DocumentType::class)->findAll();
			
			$paginatedDocuments = $this->paginator->paginate(
				$this->entityManager->getRepository(Document::class)->findAll(),
				$this->requestStack->getCurrentRequest()->query->getInt('page', 1),
				10
			);
			$searchForm = $this->createFormBuilder()
				->add('q', SearchType::class, [
					'label' => false,
					'attr' => [
						'placeholder' => 'Rechercher un document',
						'class' => 'form-control-sm',
					],
				])
				->add('submit', SubmitType::class, [
					'label' => 'Rechercher',
					'attr' => [
						'class' => 'btn btn-sm btn-primary',
					],
				])
				->getForm();
			$searchForm->handleRequest($this->requestStack->getCurrentRequest());
			if ($searchForm->isSubmitted() && $searchForm->isValid()) {
				$q = $searchForm->getData()['q'];
				$paginatedDocuments = $this->paginator->paginate(
					$this->entityManager->getRepository(Document::class)->findBySearch($q),
					$this->requestStack->getCurrentRequest()->query->getInt('page', 1),
					10
				);
			}
			return $this->render('dashboard/ged/index.html.twig', [
				'controller_name' => 'Ged',
				'parentController' => 'Gestion des documents',
				'documents' => $paginatedDocuments,
				'documentTypes' => $documentTypes,
				'searchForm' => $searchForm->createView(),
			]);
		}
		#[Route('/search/{q}', name: 'documents_by_search')]
		public function bysearch($q):Response
		{
			$paginatedDocuments = $this->paginator->paginate(
				$this->entityManager->getRepository(Document::class)->findAll(),
				$this->requestStack->getCurrentRequest()->query->getInt('page', 1),
				10
			);
			$searchForm = $this->createFormBuilder()
				->add('q')
				->getForm();
			$searchForm->handleRequest($this->requestStack->getCurrentRequest());
			if ($searchForm->isSubmitted() && $searchForm->isValid()) {
				$q = $searchForm->getData()['q'];
				$paginatedDocuments = $this->paginator->paginate(
					$this->entityManager->getRepository(Document::class)->findBySearch($q),
					$this->requestStack->getCurrentRequest()->query->getInt('page', 1),
					10
				);
			}
			return $this->render('dashboard/ged/index.html.twig', [
				'controller_name' => 'Ged',
				'parentController' => 'Gestion des documents',
				'documents' => $paginatedDocuments,
				'searchForm' => $searchForm->createView(),
			]);
		}
		#[Route('/type/{id}', name: 'documents_by_type')]
		public function byType($id):Response
		{
			$documentType = $this->entityManager->getRepository(DocumentType::class)->findOneById($id);
			
			$paginatedDocuments = $this->paginator->paginate(
				$this->entityManager->getRepository(Document::class)->findByType($documentType),
				$this->requestStack->getCurrentRequest()->query->getInt('page', 1),
				10
			);
			return $this->render('dashboard/ged/documents_by_types.html.twig', [
				'controller_name' => 'Documents '.$documentType->getName(),
				'parentController' => 'GED',
				'documents' => $paginatedDocuments,
				'type'=> $documentType,
			]);
		}
		
	}