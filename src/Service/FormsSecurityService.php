<?php
	
	namespace App\Service;
	use Doctrine\ORM\EntityManagerInterface;
	use Psr\Log\LoggerInterface;
	use Symfony\Bridge\Twig\Mime\TemplatedEmail;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
	use Symfony\Component\Mailer\MailerInterface;
	use Symfony\Component\Mime\Email;
	
	class FormsSecurityService extends AbstractController
	{
		public function __construct(
		private LoggerInterface $logger,
		){}
		
		public function validateForm($recaptchaResponse,$formData,$recaptchaAction,$csrfToken,$csrf_id):bool
		{
			//dd($recaptchaResponse);
			if(isset($recaptchaResponse)){
			
			if($recaptchaResponse->getScore() < 0.5 || $recaptchaResponse->getScore() == null || $recaptchaResponse->getAction() !== $recaptchaAction){
				$formData = json_encode($formData);
				$this->logger->error('app.form.error.recaptcha'.$formData);
				
				return false;
			}
			if(!$this->isCsrfTokenValid($csrf_id, $csrfToken)){
				$formData = json_encode($formData);
				$this->logger->error('app.form.error.csrf'.$formData);
				return false;
			}
			}
			return true;
		}
		public function validateTwigForm($recaptchaResponse,$formData,$recaptchaAction,$csrfToken,$csrf_id):bool
		{
			
			
			if($recaptchaResponse["score"] < 0.5 || $recaptchaResponse["score"] == null || $recaptchaResponse["action"] !== $recaptchaAction){
				$formData = json_encode($formData);
				$recaptchaResponseCode=json_encode($recaptchaResponse["error-codes"]);
				$this->logger->error('app.form.error.recaptcha'.$formData.'-- ErrorCodes: '.$recaptchaResponseCode);
				
				return false;
			}
			if(!$this->isCsrfTokenValid($csrf_id, $csrfToken)){
				$formData = json_encode($formData);
				$this->logger->error('app.form.error.csrf'.$formData);
				return false;
			}
			return true;
		}
		
		public function checkCsrfToken($csrfToken,$csrf_id):bool
		{
			if(!$this->isCsrfTokenValid($csrf_id, $csrfToken)){
				$this->logger->error('app.form.error.csrf',[$csrfToken,$csrf_id]);
				return false;
			}
			return true;
		}
		
		public function checkRecaptcha($recaptchaResponse,$recaptchaAction):bool{
			if($recaptchaResponse->getScore() < 0.5 || $recaptchaResponse->getScore() == null || $recaptchaResponse->getAction() !== $recaptchaAction){
				$this->addFlash('error', 'app.form.error.recaptcha');
				$this->logger->error('app.form.error.recaptcha',[$recaptchaResponse->getScore(),$recaptchaResponse->getAction()]);
				return false;
			}
			return true;
			
		}
	}