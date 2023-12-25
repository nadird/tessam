<?php
	
	namespace App\Controller\Web;
	

	use App\Entity\Lead;
	use App\Entity\WebJobApplication;
	use App\Form\Web\WebJobAppicationType;
	use App\Repository\WebJobOfferRepository;
	use App\Service\ImageUploadService;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\RequestStack;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;
	use Symfony\Contracts\Translation\TranslatorInterface;
	
	#[Route('/jobs', name: 'app_web_jobs_')]
	
	
	class WebJobsController extends AbstractController
	{
		public function __construct(
			private WebJobOfferRepository $jobOfferRepository,
			private RequestStack $requestStack,
			private EntityManagerInterface $entityManager,
			private TranslatorInterface $translator,
		){
		}
		
		#[Route('/', name: 'index')]
		public function index(): Response
		{
			$jobOffers  = $this->jobOfferRepository->findAll();
			
			
			return $this->render('web/jobs/index.html.twig', [
				'controller_name' => 'Jobs list',
				'jobOffers' => $jobOffers
			]);
		}
		#[Route('/offer/{id}', name: 'offer')]
		public function offer($id,  ImageUploadService $imageUploadService): Response
		{
			$jobOffer  = $this->jobOfferRepository->findOneById($id);
			//dd($jobOffer);
			$jobapplication = new WebJobApplication();
			$jobapplication->setJobOffer($jobOffer);
			$jobApplicationform = $this->createForm(WebJobAppicationType::class, $jobapplication);
			$jobApplicationform->handleRequest($this->requestStack->getCurrentRequest());
			if ($jobApplicationform->isSubmitted() && $jobApplicationform->isValid()) {
				$jobapplication->setCreatedAt(new \DateTime());
				$jobapplication->setJobOffer($jobOffer);
				$jobapplication->setResume($imageUploadService->uploadCv(
					$jobApplicationform->get('resume')->getData(), $this->getParameter('resumes_directory')));
				$jobapplication->setIp($this->requestStack->getCurrentRequest()->getClientIp());
				$this->entityManager->persist($jobapplication);
				$this->entityManager->flush();
				
				$this->addFlash('success', 'Your application has been sent');
				return $this->redirectToRoute('app_web_jobs_merci_page');
			}
			return $this->render('web/jobs/application.html.twig', [
				'controller_name' => 'Job Application',
				'job' => $jobOffer,
				'form' => $jobApplicationform->createView()
			]);
		}
		#[Route('/thankyou', name: 'merci_page')]
		public function merci():Response
		{
			
			$controller_name = $this->translator->trans('app.product.format.thankyou.controller_name');
			
			return $this->render('web/products/merci.html.twig', [
				'controller_name' => $controller_name,
				
			
			]);
		}
	}
