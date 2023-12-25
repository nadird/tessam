<?php
	namespace App\Controller\Dashboard\webmanagement;

	use App\Entity\WebContact;
	use App\Repository\WebContactRepository;
	use Doctrine\ORM\EntityManagerInterface;
	use Knp\Component\Pager\PaginatorInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\RequestStack;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
	use Symfony\Component\Routing\Annotation\Route;
	use Symfony\Component\String\Slugger\SluggerInterface;
	use Symfony\Contracts\Translation\TranslatorInterface;
	
	#[Route('/dashboard/crm/webform/contact', name: 'app_dashboard_webform_contact_')]
	class WebContactController extends AbstractController
	{
		public function __construct(
			private EntityManagerInterface $entityManager,
			private WebContactRepository $webContactRepository,
			private RequestStack $requestStack,
			private SluggerInterface $slugger,
			private UserPasswordHasherInterface $userPasswordHasher,
			private TranslatorInterface $translator,
			private PaginatorInterface  $paginator,
		){
		}
		#[Route('/', name: 'index')]
		public function index():Response
		{
			//$contacts = $this->webContactRepository->findAll();
			$pagination = $this->paginator->paginate(
				$this->entityManager->getRepository(WebContact::class)->findAll(['id'=>'DESC']),
				$this->requestStack->getCurrentRequest()->query->getInt('page', 1),
				50
			);
			$controller_name = 'Contact Messages';
			$this->translator->trans('Contact Messages');
			return $this->render('dashboard/crm/webformcontact/index.html.twig', [
				'controller_name' => $controller_name,
				'parentController'=>'CRM',
				'contacts' => $pagination,
			]);
		}
		#[Route('/contact/{id}', name: 'contact_message')]
		public function contact($id):Response
		{
			$contact = $this->webContactRepository->findOneById($id);
			$controller_name= $this->translator->trans('Contact Message');
			
			return $this->render('dashboard/crm/webformcontact/message.html.twig', [
				'controller_name' => $controller_name,
				'parentController'=>'CRM',
				'contact' => $contact,
				
			]);
		}
		
		
		
		
	}