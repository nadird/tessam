<?php

namespace App\Controller\Web;

use App\Entity\BlogPost;
use App\Entity\CFormatBien;
use App\Repository\BlogPostRepository;
use App\Repository\CFormatBienRepository;
use App\Repository\CProjectRepository;
use App\Repository\CTypeBienRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/', name: 'app_web_')]
class WebHomeController extends AbstractController
{
	public function __construct(
		private TranslatorInterface $translator,
		private BlogPostRepository $blogPostRepository
	){
	}
	
	#[Route('/', name: 'index')]
    public function index(): Response
    {
		//dd($this->getParameter('app.maintenance_mode'));
		if($this->getParameter('app.maintenance_mode')==="true"){
			$this->addFlash('warning', 'Le site est en mode debug');
			if(!$this->getUser()){
				return $this->redirectToRoute('app_web_custom_comming_soon');
			}
		}
		
		$controller_name = $this->translator->trans('web.home.index.title');
		
        return $this->render('web/web_home/index.html.twig', [
            'controller_name' => $controller_name,
	        'blog_posts' => $this->blogPostRepository->findByIsFeatured(true),
        ]);
    }
	
}
