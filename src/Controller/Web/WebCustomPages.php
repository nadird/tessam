<?php
	
	namespace App\Controller\Web;
	
	use App\Entity\CFormatBien;
	use App\Repository\CFormatBienRepository;
	use App\Repository\CProjectRepository;
	use App\Repository\CTypeBienRepository;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;
	
	#[Route('/cust', name: 'app_web_custom_')]
	class WebCustomPages extends AbstractController
	{
		public function __construct(
		){
		}
		
		#[Route('/comingSoon', name: 'comming_soon')]
		public function commingsoon(): Response
		{
			//dd('hello');
			return $this->render('web/_custom_pages/coming_soon.html.twig');
		}
		#[Route('/merci', name: 'thankyou')]
		public function thankyou(): Response
		{
			return $this->render('web/products/merci.html.twig',[
				'controller_name'=>'Merci',
			]);
		}
	}