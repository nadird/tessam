<?php
	
	namespace App\Controller\Web;
	
	use App\Repository\WebPageRepository;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;
	
	
	class WebPagesDisplay extends AbstractController
	{
		public function __construct(private WebPageRepository $webPageRepository)
		{
		}
		
		#[Route('page/{slug}', name: 'app_web_page')]
		public function page($slug): Response
		{
			$page = $this->webPageRepository->findOneBy(['slug' => $slug]);
			
			return $this->render('web/web_pages/webpage.html.twig', [
				'controller_name' => 'WebHomeController',
			]);
		}
		
		#[Route('about', name: 'app_web_page_about')]
		public function about(): Response
		{
			
			return $this->render('web/web_pages/about_page.html.twig', [
				'controller_name' => 'WebHomeController',
			]);
		}
	}
