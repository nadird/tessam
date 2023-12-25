<?php
	
	namespace App\Controller\Web;
	
	use App\Entity\WebContact;
	use App\Form\Web\WebContactType;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\RequestStack;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;
	
	#[Route('/contact', name: 'app_web_page_contact_')]
	class WebContactController extends AbstractController
	{
		#[Route('/', name: 'index')]
		public function index(EntityManagerInterface $entityManager, RequestStack $requestStack): Response
		{
			$contact = new WebContact();
			$contactForm = $this->createForm(WebContactType::class, $contact);
			$contactForm->handleRequest($requestStack->getCurrentRequest());
			if ($contactForm->isSubmitted() && $contactForm->isValid()) {
				$entityManager->persist($contact);
				$entityManager->flush();
				$this->addFlash('success', 'Votre message a été envoyé avec succès');
				return $this->redirectToRoute('app_web_page_contact_index');
			}
			return $this->render('web/web_pages/contact.html.twig', [
				'controller_name' => 'Contact Controller',
				'form' => $contactForm->createView(),
			]);
		}
	}
