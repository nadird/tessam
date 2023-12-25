<?php
	
	namespace App\Service;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Bridge\Twig\Mime\TemplatedEmail;
	use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
	use Symfony\Component\Mailer\MailerInterface;
	use Symfony\Component\Mime\Email;
	
	class EmailService
	{
		public function __construct(
			private EntityManagerInterface $entityManager,
			private MailerInterface $mailer
		){
		
		}
		public function sendmail(): void
		{
			$email = (new TemplatedEmail())
				->from('contact@evact.dz')
				->to('b95288c1be-3bc95b+1@inbox.mailtrap.io')
				// ->replyTo('fabien@example.com')
				// ->priority(Email::PRIORITY_HIGH)
				->subject('Nouvel Inscrit sur le formulaire');
				$email->htmlTemplate('email/welcome.html.twig');
				
			
			try {
				$this->mailer->send($email);
				dd('sent');
				
			} catch (\Exception|TransportExceptionInterface $e) {
			dd($e);
			}
			
		}
		
	}