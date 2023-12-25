<?php
	
	namespace App\Controller\Web;
	
	
	use App\Entity\Configuration;
	use App\Entity\CProject;
	use App\Entity\CTypeBien;
	use App\Repository\BlogCategoryRepository;
	use App\Repository\BlogPostRepository;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\RequestStack;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;
	
	#[Route('/utils', name: 'app_web_utils_')]
	class UtilsController extends AbstractController
	{
		public function __construct(
			private EntityManagerInterface $entityManager,
			private RequestStack $requestStack,
		){
		
		}
		
		
		public function menuWebRendrer(): Response
		{
			$types = $this->entityManager->getRepository(CTypeBien::class)->findAll();
			$projects = $this->entityManager->getRepository(CProject::class)->findByIsActive(1);
			
			return $this->render('web/_partials/_dynamic_main_menu.html.twig', [
				'types'=>$types,
				'projects'=>$projects,
			]);
		}
		public function phonesMails(): Response
		{
			$phone= $this->entityManager->getRepository(Configuration::class)->findOneBy(['configkey'=>'PHONE_NUMBER_01']);
			$email= $this->entityManager->getRepository(Configuration::class)->findOneBy(['configkey'=>'CONTACT_MAIL']);
			
			
			return $this->render('web/_partials/_dynamic_topbar_phones_email.html.twig', [
				'email'=>$email,
				'phone'=>$phone,
			
			]);
		}
		//socialMedia
		public function socialMedia(): Response
		{
			$facebook= $this->entityManager->getRepository(Configuration::class)->findOneBy(['configkey'=>'FACEBOOK_PAGE_LINK']);
			$instagram= $this->entityManager->getRepository(Configuration::class)->findOneBy(['configkey'=>'INSTAGRAM_PAGE_LINK']);
			$twitter= $this->entityManager->getRepository(Configuration::class)->findOneBy(['configkey'=>'TWITTER_PAGE_LINK']);
			$linkedin= $this->entityManager->getRepository(Configuration::class)->findOneBy(['configkey'=>'LINKEDIN_PAGE_LINK']);
			$Messenger= $this->entityManager->getRepository(Configuration::class)->findOneBy(['configkey'=>'MESSENGER_MESSAGE_SEND_LINK']);
			$Whatsapp= $this->entityManager->getRepository(Configuration::class)->findOneBy(['configkey'=>'WHATSAPP_MESSAGE_SEND_LINK']);
			$Viber= $this->entityManager->getRepository(Configuration::class)->findOneBy(['configkey'=>'VIBER_MESSAGE_SEND_LINK']);
			$Email= $this->entityManager->getRepository(Configuration::class)->findOneBy(['configkey'=>'CONTACT_MAIL']);
			$Phone= $this->entityManager->getRepository(Configuration::class)->findOneBy(['configkey'=>'PHONE_NUMBER_01']);
			$tiktok= $this->entityManager->getRepository(Configuration::class)->findOneBy(['configkey'=>'TIKTOK_PAGE_LINK']);
			$sm =[
				'facebook'=> $facebook,
				'instagram'=> $instagram,
				'twitter'=> $twitter,
				'linkedin'=> $linkedin,
				'Messenger'=> $Messenger,
				'Whatsapp'=> $Whatsapp,
				'Viber'=> $Viber,
				'Email'=> $Email,
				'Phone'=> $Phone,
				'tiktok'=> $tiktok,];
			
			return $this->render('web/_partials/_dynamic_topbar_sm.html.twig', [
				//'email'=>$email,
				//'phone'=>$phone,
				'sm'=>$sm,
			
			]);
		}
		
	}