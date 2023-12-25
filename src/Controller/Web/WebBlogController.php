<?php
	
	namespace App\Controller\Web;
	

	use App\Repository\BlogCategoryRepository;
	use App\Repository\BlogPostRepository;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\RequestStack;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;
	
	#[Route('/blog', name: 'app_web_blog_')]
	class WebBlogController extends AbstractController
	{
		public function __construct(
			private BlogCategoryRepository $blogCategoryRepository,
			private EntityManagerInterface $entityManager,
			private RequestStack $requestStack,
			private BlogPostRepository $blogPostRepository,
		){
		}
		
		#[Route('/', name: 'index')]
		public function index(): Response
		{
			$blogPosts = $this->blogPostRepository->findAll();
			$blogCategories= $this->blogCategoryRepository->find3activeNotFirst();
			$acceuil = $this->blogCategoryRepository->findOneById(1);
			
			return $this->render('web/blog/index.html.twig', [
				'controller_name' => 'BlogIndexController',
				'blogPosts' => $blogPosts,
				'blogCategories' => $blogCategories,
				'acceuil' => $acceuil,
			]);
		}
		#[Route('/category/{slug}', name: 'category')]
		public function category($slug, EntityManagerInterface $entityManager, RequestStack $requestStack): Response
		{
			$blogPosts = $this->blogPostRepository->findByCategory(
				$this->blogCategoryRepository->findOneBy(['slug' => $slug])
			);
			return $this->render('web/blog/category.html.twig', [
				'controller_name' => 'BlogIndexController',
				'blogPosts' => $blogPosts,
			
			
			]);
		}
		#[Route('/post/{slug}', name: 'post')]
		public function post($slug, EntityManagerInterface $entityManager, RequestStack $requestStack): Response
		{
			$blogPost = $this->blogPostRepository->findOneBy(['slug' => $slug]);
			return $this->render('web/blog/post.html.twig', [
				'controller_name' => 'BlogIndexController',
				'blogPost' => $blogPost,
			
			
			]);
		}
		
	}
