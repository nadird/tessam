<?php

namespace App\Controller\Dashboard\webmanagement;

use App\Entity\BlogPost;
use App\Form\Dashboard\webconfig\BlogPostType;
use App\Repository\BlogPostRepository;
use App\Service\ImageUploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/dashboard/web/blog/post', name: 'app_dashboard_web_blog_post_')]
class BlogPostController extends AbstractController
{
	public function __construct(
		private BlogPostRepository $blogPostRepository,
		private EntityManagerInterface $entityManager,
		private RequestStack $requestStack,
		private ImageUploadService $imageUploadService,
		private SluggerInterface $slugger,
	)
	{
	}
	
	#[Route('/', name: 'index', methods: ['GET'])]
    public function index(BlogPostRepository $blogPostRepository): Response
    {
        return $this->render('dashboard/webmanagement/blog_post/index.html.twig', [
            'blog_posts' => $blogPostRepository->findAll(),
	        'controller_name' => 'BlogPostController',
	        'parentController'=>'WebmanagementController',
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(): Response
    {
        $blogPost = new BlogPost();
        $form = $this->createForm(BlogPostType::class, $blogPost);
        $form->handleRequest($this->requestStack->getCurrentRequest());

        if ($form->isSubmitted() && $form->isValid()) {
			//dd($blogPost);
			$blogPost->setCreatedAt(new \DateTime());
			$blogPost->setLastModified(new \DateTime());
			$blogPost->setAuthor($this->getUser());
			if($blogPost->getThumbnail() !==null){
				$blogPost->setThumbnail(
					$this->imageUploadService->resizeuploadImage($blogPost->getThumbnail(),$this->getParameter('blog_directory'),[300,300])
				);
			}
			if($blogPost->getImage() !==null){
				$blogPost->setImage($this->imageUploadService->uploadImage($blogPost->getImage(),$this->getParameter('blog_directory')));
			}
			if($blogPost->getImgOg() !==null){
				$blogPost->setImgOg($this->imageUploadService->uploadImage($blogPost->getImgOg(),$this->getParameter('blog_directory')));
			}
			if($blogPost->getSlug() === null){
				$blogPost->setSlug($this->slugger->slug($blogPost->getPublicTitle()));
			}
            $this->entityManager->persist($blogPost);
	        $this->entityManager->flush();
            return $this->redirectToRoute('app_dashboard_web_blog_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/webmanagement/blog_post/new.html.twig', [
            'blog_post' => $blogPost,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(BlogPost $blogPost): Response
    {
        return $this->render('blog_post/show.html.twig', [
            'blog_post' => $blogPost,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(BlogPost $blogPost, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BlogPostType::class, $blogPost);
        $form->handleRequest($this->requestStack->getCurrentRequest());
        if ($form->isSubmitted()) {
			$files = $this->requestStack->getCurrentRequest()->files->get('blog_post');
	        if($files['thumbnail'] !==null){
		        $blogPost->setThumbnail(
			        $this->imageUploadService->resizeuploadImage($files['thumbnail'],$this->getParameter('blog_directory'),[300,300])
		        );
	        }
	        if($files['image'] !==null){
		        $blogPost->setImage($this->imageUploadService->uploadImage($files['image'],$this->getParameter('blog_directory')));
	        }
	        if($files['imgOg'] !==null){
		        $blogPost->setImgOg($this->imageUploadService->uploadImage($files['imgOg'],$this->getParameter('blog_directory')));
	        }
	        if($blogPost->getSlug() === null){
		        $blogPost->setSlug($this->slugger->slug($blogPost->getPublicTitle()));
	        }
			
            $entityManager->flush();
			

            return $this->redirectToRoute('app_dashboard_web_blog_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/webmanagement/blog_post/edit.html.twig', [
            'blog_post' => $blogPost,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, BlogPost $blogPost, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blogPost->getId(), $request->request->get('_token'))) {
            $entityManager->remove($blogPost);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dashboard_web_blog_post_index', [], Response::HTTP_SEE_OTHER);
    }
}
