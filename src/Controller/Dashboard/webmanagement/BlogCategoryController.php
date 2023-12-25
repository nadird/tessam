<?php

namespace App\Controller\Dashboard\webmanagement;

use App\Entity\BlogCategory;
use App\Form\Dashboard\webconfig\BlogCategoryType;
use App\Repository\BlogCategoryRepository;
use App\Service\ImageUploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/dashboard/web/blog/category', name: 'app_dashboard_web_blog_category_')]
class BlogCategoryController extends AbstractController
{
	public function __construct(
		private ImageUploadService $imageUploadService,
		private SluggerInterface $slugger
		
	)
	{
	}
	
	#[Route('/', name: 'index', methods: ['GET'])]
    public function index(BlogCategoryRepository $blogCategoryRepository): Response
    {
	    
		
        return $this->render('dashboard/webmanagement/blog_category/index.html.twig', [
            'blog_categories' => $blogCategoryRepository->findAll(),
	        'controller_name' => 'BlogCategoryController',
	        'parentController' => 'WebmanagementController',
	        
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $blogCategory = new BlogCategory();
        $form = $this->createForm(BlogCategoryType::class, $blogCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			$blogCategory->setCreatedAt(new \DateTime());
			if($form->get('thumbnail')->getData()){
				$blogCategory->setThumbnail(
					$this->imageUploadService->uploadCv(
						$form->get('thumbnail')->getData(),
						$this->getParameter('blog_directory')
					
					));
			}
			if($form->get('image')->getData()){
				$blogCategory->setImage(
					$this->imageUploadService->uploadCv(
						$form->get('image')->getData(),
						$this->getParameter('blog_directory')
					
					));
			}
			if($blogCategory->getSlug() == null){
				$blogCategory->setSlug(
					$this->slugger->slug(
						$blogCategory->getName()
					)
				);
			}
            $entityManager->persist($blogCategory);
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_web_blog_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/webmanagement/blog_category/new.html.twig', [
            'blog_category' => $blogCategory,
            'form' => $form,
	        'controller_name' => 'BlogCategoryController',
	        'parentController' => 'WebmanagementController',
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(BlogCategory $blogCategory): Response
    {
        return $this->render('dashboard/webmanagement/blog_category/show.html.twig', [
            'blog_category' => $blogCategory,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BlogCategory $blogCategory, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BlogCategoryType::class, $blogCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_web_blog_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/webmanagement/blog_category/edit.html.twig', [
            'blog_category' => $blogCategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, BlogCategory $blogCategory, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blogCategory->getId(), $request->request->get('_token'))) {
            $entityManager->remove($blogCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dashboard_web_blog_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
