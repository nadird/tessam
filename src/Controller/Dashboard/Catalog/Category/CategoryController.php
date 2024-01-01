<?php
	namespace App\Controller\Dashboard\Catalog\Category;
	
	
	use App\Entity\Category;
	use App\Entity\Product;
	use App\Form\Dashboard\Catalog\Category\CategoryIndexFiltersFormType;
	use App\Form\Dashboard\Catalog\Category\NewCategoryFormType;
	use App\Form\Dashboard\Catalog\Product\ProductIndexFiltersFormType;
	use App\Repository\CategoryRepository;
	use App\Repository\ProductRepository;
	use Doctrine\ORM\EntityManagerInterface;
	use Knp\Component\Pager\PaginatorInterface;
	use Psr\Log\LoggerInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\RequestStack;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;
	use Symfony\Component\String\Slugger\SluggerInterface;
	use Symfony\Contracts\Translation\TranslatorInterface;
	
	
	#[Route('dashboard/Catalog/Category', name: 'app_dashboard_catalog_Category_')]
	class CategoryController extends AbstractController
	{
		public function __construct(
			private EntityManagerInterface $entityManager,
			private RequestStack $requestStack,
			private ProductRepository $productRepository,
			private CategoryRepository $categoryRepository,
			private LoggerInterface $logger,
			private PaginatorInterface $paginator,
			//private TranslatableInterface $translator,
			//private TranslatorInterface $translator,
			private SluggerInterface $slugger,
	
		){
		}
		#[Route('/', name: 'index',methods: ['GET','POST'])]
		public function index(TranslatorInterface $translator):Response
		{
			
			$query = $this->categoryRepository->createQueryBuilder('c')
				->where('c.isActive = :isActive')
				->setParameter('isActive',true)
				->andWhere('c.isDeleted = :isDeleted')
				->setParameter('isDeleted',false)
				->orderBy('c.id', 'ASC')
				->getQuery();
			$filtersForm = $this->createForm(CategoryIndexFiltersFormType::class);
			$filtersForm->handleRequest($this->requestStack->getMainRequest());
			if($filtersForm->isSubmitted() && $filtersForm->isValid()){
				$filters = $filtersForm->getData();
				$query= $this->handleIndexFiltersForm($filters);
				$pagination = $this->paginator->paginate(
					$query,
					$this->requestStack->getMainRequest()->query->getInt('page', 1),
					10
				);
				return $this->render('dashboard/Catalog/Category/index.html.twig', [
					//'controller_name' => new TranslatableMessage('app.dashboard.catalog.product.index.controller_name') ,
					'controller_name' => $translator->trans('app.dashboard.catalog.category.index.controller_name',[],'dashboard') ,
					'parentController' => $translator->trans('app.dashboard.catalog.category.index.parent_controller_name',[],'dashboard'),
					'parentControllerRoute' => 'app_dashboard_home_index',
					'parentControllerRouteParams' => [],
					'categories' => $pagination,
					'filtersForm' => $filtersForm->createView(),
				],response: new Response(null,$filtersForm->isSubmitted() ? 422 : 200));
			}
			$pagination = $this->paginator->paginate(
				$query,
				$this->requestStack->getMainRequest()->query->getInt('page', 1),
				10
			);
			
			$pagination->setCustomParameters([
				'align' => 'center',
				'size' => 'small',
				'style' => 'bottom',
				'rounded' => true,
				'classes' => 'pagination',
			]);
			//$pagination->setTemplate('dashboard/Catalog/Product/pagination.html.twig');
			
			
			return $this->render('dashboard/Catalog/Category/index.html.twig', [
				//'controller_name' => new TranslatableMessage('app.dashboard.catalog.product.index.controller_name') ,
				'controller_name' => $translator->trans('app.dashboard.catalog.category.index.controller_name',[],'dashboard') ,
				'parentController' => $translator->trans('app.dashboard.catalog.category.index.parent_controller_name',[],'dashboard'),
				'parentControllerRoute' => 'app_dashboard_home_index',
				'parentControllerRouteParams' => [],
				'categories' => $pagination,
				'filtersForm' => $filtersForm->createView(),
			],response: new Response(null,$filtersForm->isSubmitted() ? 422 : 200));
		}
		
		#[Route('/show/{id}', name: 'show',methods: ['GET'])]
		public function show(Category $category,TranslatorInterface $translator):Response
		{
			
			if(!$category){
				$this->addFlash('danger','app.dashboard.catalog.category.show.flash_danger');
				return $this->redirectToRoute('app_dashboard_catalog_category_index');
			}
			
			return $this->render('dashboard/Catalog/Category/show.html.twig', [
				'controller_name' => $translator->trans('app.dashboard.catalog.category.show.controller_name',[],'dashboard').$category->getName(),
				'parentController' => $translator->trans('app.dashboard.catalog.category.show.parent_controller_name',[],'dashboard'),
				'parentControllerRoute' => 'app_dashboard_catalog_Category_index',
				'parentControllerRouteParams' => [],
				'product' => $category,
			]);
		}
		
		#[Route('/new', name: 'new',methods: ['GET','POST'])]
		public function new( TranslatorInterface $translator):Response
		{
			$form = $this->createForm(NewCategoryFormType::class);
			$form->handleRequest($this->requestStack->getMainRequest());
			if($form->isSubmitted() && $form->isValid()){
				$category = $form->getData();
				$category->setSlug($this->slugger->slug($category->getName()));
				
				$this->entityManager->persist($category);
				$this->entityManager->flush();
				$this->addFlash('success','app.dashboard.catalog.category.new.flash_success');
				return $this->redirectToRoute('app_dashboard_catalog_Category_index');
			}
			return $this->render('dashboard/Catalog/Category/new.html.twig', [
				'controller_name' => $translator->trans('app.dashboard.catalog.category.new.controller_name',[],'dashboard'),
				'parentController' => $translator->trans('app.dashboard.catalog.category.new.parent_controller_name',[],'dashboard'),
				'parentControllerRoute' => 'app_dashboard_catalog_Category_index',
				'parentControllerRouteParams' => [],
				'form' => $form->createView(),
			]);
		}
		
		#[Route('/edit/{id}', name: 'edit',methods: ['GET','POST'])]
		public function edit(Category $category, TranslatorInterface $translator):Response
		{
			return $this->render('dashboard/Catalog/Category/edit.html.twig', [
				'controller_name' => $translator->trans('app.dashboard.catalog.category.edit.controller_name',[],'dashboard').' '.$category->getName(),
				'parentController' => $translator->trans('app.dashboard.catalog.category.edit.parent_controller_name',[],'dashboard'),
				'parentControllerRoute' => 'app_dashboard_catalog_Category_index',
				'parentControllerRouteParams' => [
					'id' => $category->getId(),
				],
			]);
		}
	
		
		
		public function productsIndexQueryBuilder($query,array $Options)
		{
			$query->andWhere('p.isActive = :isActive')
				->setParameter('isActive',true);
			
			
			return $query;
		}
		public function handleIndexFiltersForm($filters)
		{
			$query = $this->categoryRepository->createQueryBuilder('c');
			
			if($filters['name']){
				$query->andWhere('c.name LIKE :name')
					->setParameter('name','%'.$filters['name'].'%');
			}
			if($filters['isActive'] !== null){
				$query->andWhere('c.isActive = :isActive')
					->setParameter('isActive',$filters['isActive']);
			}
			if($filters['isDeleted'] !== null){
				$query->andWhere('c.isDeleted = :isDeleted')
					->setParameter('isDeleted',$filters['isDeleted']);
			}
			$query->orderBy('c.id', 'ASC');
			$query = $query->getQuery();
			return $query;
		}
		
		
	}