<?php
	namespace App\Controller\Web;
	

	use App\Entity\ContactJob;
	use App\Entity\Employee;
	use App\Entity\InteractionHistory;
	use App\Entity\InteractionSource;
	use App\Entity\InteractionType;
	use App\Entity\Lead;
	use App\Entity\LeadSource;
	use App\Entity\LeadState;
	use App\Entity\Notification;
	use App\Entity\NotificationType;
	use Doctrine\ORM\EntityManagerInterface;
	use Psr\Log\LoggerInterface;
	use Symfony\Component\HttpFoundation\RequestStack;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\Routing\Annotation\Route;
	use Symfony\Contracts\HttpClient\HttpClientInterface;
	
	class WebProspectForms extends AbstractController {
		
		
		public function __construct(
			private EntityManagerInterface $em,
			private RequestStack $requestStack,
			private LoggerInterface $logger,
			private HttpClientInterface $httpClient,
			//private Recaptcha3 $recaptcha3,
			
		){
		}
		
		#[Route('/callback/submit', name: 'app_callback_submit' , methods: ['POST'])]
		public function prospectCallback(): Response {
			$request = $this->requestStack->getCurrentRequest();
			$referer = $request->headers->get('referer');
			$formData = $request->request->all();
			$recaptchaResponse = $formData['_captcha'] ?? null;
			$csrfToken = $formData['_token'] ?? null;
			$csrfId = 'prospects_leads_form';
			$recaptchaAction = 'landing_page_callback';
			$secretKey = '6LfuI_AoAAAAAL3aBv_jLv6wh7Noenf-ICZJX8-h';
			
			if (!$this->isCsrfTokenValid($csrfId, $csrfToken)) {
				return $this->logAndRedirect('CSRF token validation failed.'.' || Form data : '.json_encode($formData), $referer);
			}
			try {
				$response = $this->httpClient->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
					'body' => [
						'secret' => $secretKey,
						'response' => $recaptchaResponse,
					]
				]);
				
				$recaptchaData = $response->toArray();
				
				if (!$this->isValidRecaptcha($recaptchaData, $recaptchaAction)) {
					return $this->logAndRedirect('reCAPTCHA validation failed.', $referer);
				}
			} catch (\Exception $e) {
				return $this->logAndRedirect('reCAPTCHA server error: ' . $e->getMessage(), $referer);
			}
			$lead = $this->createAndSaveLead($formData);
			$this->addAdditionalActions($lead);
			
			return $this->redirectToRoute('app_web_custom_thankyou',[
				'page' => $referer,
				'lead' => $lead->getId(),
			]);
			
		}
		private function logAndRedirect(string $message, string $referer): Response
		{
			$this->logger->error($message);
			return $this->redirect($referer);
		}
		private function isValidRecaptcha(array $recaptchaData, string $recaptchaAction): bool
		{
			return isset($recaptchaData['success']) && $recaptchaData['success'] === true &&
				isset($recaptchaData['score']) && $recaptchaData['score'] >= 0.5 &&
				isset($recaptchaData['action']) && $recaptchaData['action'] === $recaptchaAction;
		}
		private function createAndSaveLead(array $formData): Lead
		{
			$lead = new Lead();
			$lead->setEmail1($formData['email']);
			$lead->setPhone1($formData['phone']);
			$lead->setFirstName($formData['firstname']);
			$lead->setLastName($formData['lastname']);
			$lead->setSource($this->em->getRepository(LeadSource::class)->findOneBy(['code' => 'WEB']));
			$lead->setState($this->em->getRepository(LeadState::class)->findOneBy(['code' => 'NEW']));
			$lead->setDateAdd(new \DateTime());
			$lead->setLastInteraction(new \DateTime());
			$lead->setIsValid(false);
			$lead->setIsDeleted(false);
			$lead->setIsProcessed(false);
			$lead->setDateUpdated(new \DateTime());
			$lead->setIsTransformedToCustomer(false);
			$lead->setAddress(' ');
			$lead->setJob($this->em->getRepository(ContactJob::class)->findOneBy(['code' => 'INV']));
			$this->em->persist($lead);
			$this->em->flush();
			
			return $lead;
		}
		private function addAdditionalActions(Lead $lead): void
		{
			$interaction = new InteractionHistory();
			$interaction->setDateAdd(new \DateTime());
			$interaction->setLead($lead);
			$interaction->setIsDeleted(false);
			$interaction->setText('Demande de rappel');
			$interaction->setLeadState($lead->getState());
			$interaction->setSource($this->em->getRepository(InteractionSource::class)->findOneBy(['code' => 'SYS']));
			$interaction->setType($this->em->getRepository(InteractionType::class)->findOneBy(['code' => 'AUT']));
			$this->em->persist($interaction);
			$this->em->flush();
			$notification = new Notification();
			$notification->setDateAdd(new \DateTime());
			$notification->setIsDeleted(false);
			$notification->setIsProcessed(false);
			$notification->setType($this->em->getRepository(NotificationType::class)->findOneBy(['code' => 'AUT']));
			$notification->setEmployee($this->em->getRepository(Employee::class)->findOneBy(['roles' => 'ROLE_DC']));
			$routing = $this->generateUrl('app_dashboard_crm_leads_lead_page', ['id' => $lead->getId()]);
			$notification->setText('Nouvelle demande de rappel '. '<a href="'.$routing.'">Voir</a>');
			$this->em->persist($notification);
			$this->em->flush();
		}
		
	}