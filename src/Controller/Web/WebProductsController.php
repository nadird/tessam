<?php
	namespace App\Controller\Web;
	
	use App\Entity\CFormatBien;
	use App\Entity\Compaign;
	use App\Entity\ContactSource;
	use App\Entity\CProject;
	use App\Entity\CTypeBien;
	use App\Entity\Lead;
	use App\Entity\LeadSource;
	use App\Entity\LeadState;
	use App\Form\Dashboard\crm\leads\NewLeadWebFormType;
	use App\Form\Dashboard\crm\Products\CTypeBienType;
	use App\Service\FormsSecurityService;
	use App\Service\TodoListTemplateAffectation;
	use Doctrine\ORM\EntityManagerInterface;
	use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
	use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3Validator;
	use Psr\Log\LoggerInterface;
	use Symfony\Bridge\Monolog\Logger;
	use Symfony\Component\HttpFoundation\RequestStack;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\Routing\Annotation\Route;
	use Symfony\Contracts\Translation\TranslatorInterface;
	
	class WebProductsController extends AbstractController {
		
		
		public function __construct(
			private EntityManagerInterface $em,
			private RequestStack $requestStack,
			private TodoListTemplateAffectation $todoListTemplateAffectation,
			private LoggerInterface $logger,
			private Recaptcha3Validator $recaptcha3Validator,
			private FormsSecurityService $formsSecurityService,
			private TranslatorInterface $translator,
		){
		}
		
		#[Route('/project/{slug}', name: 'app_product_project_page')]
		public function project($slug): Response {
			$project = $this->em->getRepository(CProject::class)->findOneBy(['slug' => $slug]);
			$lowestPrice = 0;
			//dd($project);
			if(!$project){
				return $this->redirectToRoute('app_web_index');
			}
			if($project->getCFormatBiens()){
				foreach ($project->getCFormatBiens() as $format){
					//dd($format->getSlug());
					if($lowestPrice == 0){
						$lowestPrice = $format->getPrice();
					}
					if($lowestPrice > $format->getPrice()){
						$lowestPrice = $format->getPrice();
					}
				}
			}
			
			
			return $this->render('web/products/project_page.html.twig', [
				'controller_name' => $project->getPublicName(),
				'project' => $project,
				'lowestPrice' => $lowestPrice,
			]);
		}
		
		#[Route('/types', name: 'app_product_types_index')]
		public function types(): Response {
			$types = $this->em->getRepository(CTypeBien::class)->findAll();
			$controller_name = $this->translator->trans('app.product.type.controller_name');
			return $this->render('web/products/index.html.twig', [
				'controller_name' => $controller_name,
				'types' => $types,
			]);
		}
		#[Route('/type/{slug}', name: 'app_product_type_page')]
		public function type($slug): Response {
			$type = $this->em->getRepository(CTypeBien::class)->findOneBy(['slug' => $slug]);
			$projectTypes = [];
			
			foreach ($type->getCFormatBiens() as $format) {
				$project = $format->getProject();
				$typeKey = $type->getId(); // Assuming you have a unique identifier for types
				$projectKey = spl_object_id($project);
				
				if (!isset($projectTypes[$projectKey])) {
					$projectTypes[$projectKey] = [
						'project' => $project,
						'types' => [],
					];
				}
				

				$projectTypes[$projectKey]['types'][] = $format;
			}
			
			
			return $this->render('web/products/type_page.html.twig', [
				'controller_name' => $type->getName(),
				'type' => $type,
				'projects' => $projectTypes,
			]);
		}
		#[Route('/formats', name: 'app_product_formats_index')]
		public function formats(): Response {
			$formats = $this->em->getRepository(CFormatBien::class)->findAll();
			
			$controller_name=$this->translator->trans('app.product.format.controller_name');
			return $this->render('web/products/index.html.twig', [
				'controller_name' => $controller_name,
				'formats' => $formats,
				
			]);
		}
		
		#[Route('/format/{slug}', name: 'app_product_format_page')]
		public function format($slug): Response {
			$format = $this->em->getRepository(CFormatBien::class)->findOneBy(['slug' => $slug]);
			$lead = new Lead();
			$form = $this->createForm(NewLeadWebFormType::class, $lead);
			$form->handleRequest($this->requestStack->getCurrentRequest());
			if($form->isSubmitted() && $form->isValid()){
				$theRequest = $this->requestStack->getCurrentRequest()->request->all();
				$lead = $this->getUtms($theRequest,$lead);
				$recaptchaResponse = $this->recaptcha3Validator->getLastResponse();
				$recaptchaAction = 'prospects_leads_form';
				$formData = $this->requestStack->getCurrentRequest()->request->all();
				$formData = $formData["new_lead_web_form"];
				$redirectroot = $this->requestStack->getCurrentRequest()->headers->get('referer');
				
				 if($recaptchaResponse->getScore() < 0.5 || $recaptchaResponse->getScore() == null || $recaptchaResponse->getAction() !== $recaptchaAction){
					$this->addFlash('error', 'app.form.error.recaptcha');
					$formData = json_encode($formData);
					$this->logger->error('app.form.error.recaptcha'.$formData);
					return $this->redirect($redirectroot, 422);
				}
				//dd($recaptchaResponse);
				$csrfToken = $this->requestStack->getCurrentRequest()->request->all();
				$csrfToken = $csrfToken["new_lead_web_form"]['_token'];
				if(!$this->isCsrfTokenValid('prospects_leads_form', $csrfToken)){
					$this->addFlash('error', 'app.form.error.csrf');
					$formData = $this->requestStack->getCurrentRequest()->request->all();
					$formData = $formData["new_lead_web_form"];
					$formData = json_encode($formData);
					$this->logger->error('app.form.error.csrf'.$formData);
					return $this->redirectToRoute('app_product_format_page',[
						'slug'=>$slug,
					]);
				}
				
				
				$lead->setIsProcessed(false);
				$lead->setIsTransformedToCustomer(false);
				$lead->setIsDeleted(false);
				$lead->setIsValid(false);
				$lead->setDateAdd(new \DateTime());
				$lead->setLastInteraction(new \DateTime());
				$lead->setDateUpdated(new \DateTime());
				$lead->addFormatBienInterest($format);
				$lead->setState($this->em->getRepository(LeadState::class)->findOneByCode('NEW'));
				
				$this->em->persist($lead);
				$this->todoListTemplateAffectation->newLeadTodoList(null,$lead);
				$this->em->flush();
				
				return $this->redirectToRoute('app_product_format_merci_page',[
					'slug'=>$slug,
					'id'=>$lead->getId(),
				]);
			}
			return $this->render('web/products/format_page.html.twig', [
				'controller_name' => $format->getName(),
				'format'=>$format,
				'form'=>$form->createView(),
			]);
			
			
		}
		
		#[Route('/thankyou/format/{slug}/{id}', name: 'app_product_format_merci_page')]
		public function merci($id):Response
		{
			$lead = $this->em->getRepository(Lead::class)->findOneById($id);
			/*
			 * TODO Dispatch a notification to DC
			 * TODO Dispatch email to lead
			 * TODO Dispatch email to DC
			 */
			$controller_name = $this->translator->trans('app.product.format.thankyou.controller_name');
			
			return $this->render('web/products/merci.html.twig', [
				'controller_name' => $controller_name,
				'lead'=>$lead,
				
			]);
		}
		public function getUtms($request,$lead){
			if(isset($theRequest["urlParams"])){
				
				$theRequest = json_decode( $theRequest["urlParams"]);
				if(isset($theRequest->utm_source)){
					$lead->setUtmSource($theRequest->utm_source);
					if($theRequest->utm_source != null && $theRequest->utm_source != '' && $this->em->getRepository(LeadSource::class)->findOneBy(['code'=>$theRequest->utm_source]) != null){
						$lead->setSource(
							$this->em->getRepository(LeadSource::class)->findOneBy(['code'=>$theRequest->utm_source])
						);
					}else{
						$lead->setSource(
							$this->em->getRepository(LeadSource::class)->findOneById(1)
						);
					}
				}
				if(isset($theRequest->utm_campaign)){
					if($theRequest->utm_campaign != null && $theRequest->utm_campaign != '' && $this->em->getRepository(Compaign::class)->findOneBy(['id'=>$theRequest->utm_campaign]) != null){
						$lead->setUtmCompaign(
							$this->em->getRepository(Compaign::class)->findOneBy(['id'=>$theRequest->utm_campaign])
						);
					}
				}
				
			}
			return $lead;
		}
	}