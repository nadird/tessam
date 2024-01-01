<?php
	namespace App\Controller\Dashboard\Catalog\Product;
	
	
	use App\Entity\Product;
	use App\Entity\Warehouse;
	use App\Form\Dashboard\Catalog\Product\NewProductFormType;
	use App\Form\Dashboard\Catalog\Product\ProductIndexFiltersFormType;
	use App\Form\Dashboard\Catalog\Product\wizard\WizardNewProductFormFirst;
	use App\Form\Dashboard\Catalog\Product\wizard\WizardNewProductFormPrix;
	use App\Form\Dashboard\Catalog\Product\wizard\WizardNewProductFormSecond;
	use App\Repository\ProductRepository;
	use Doctrine\ORM\EntityManagerInterface;
	use Knp\Component\Pager\PaginatorInterface;
	use Psr\Log\LoggerInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\RequestStack;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;
	use Symfony\Contracts\Translation\TranslatorInterface;
	
	
	#[Route('dashboard/Catalog/Product', name: 'app_dashboard_catalog_Product_')]
	class ProductsController extends AbstractController
	{
		public function __construct(
			private EntityManagerInterface $entityManager,
			private RequestStack $requestStack,
			private ProductRepository $productRepository,
			private LoggerInterface $logger,
			private PaginatorInterface $paginator,
			//private TranslatableInterface $translator,
			//private TranslatorInterface $translator,
	
		){
		}
		#[Route('/', name: 'index',methods: ['GET'])]
		public function index(TranslatorInterface $translator):Response
		{
			/**@TODO: add filters by role by shop by team */
			$query = $this->productRepository->createQueryBuilder('p')
				->where('p.isActive = :isActive')
				->setParameter('isActive',true)
				->andWhere('p.isDeleted = :isDeleted')
				->setParameter('isDeleted',false)
				->andWhere('p.isActive = :isPublished')
				->setParameter('isPublished',true)
				->orderBy('p.id', 'ASC')
				->getQuery();
			
			$filtersForm = $this->createForm(ProductIndexFiltersFormType::class);
			$filtersForm->handleRequest($this->requestStack->getMainRequest());
			if($filtersForm->isSubmitted() && $filtersForm->isValid()){
				$filters = $filtersForm->getData();
				$query= $this->handleIndexFiltersForm($filters,$query);
				
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
			
			
			return $this->render('dashboard/Catalog/Product/index.html.twig', [
				//'controller_name' => new TranslatableMessage('app.dashboard.catalog.product.index.controller_name') ,
				'controller_name' => $translator->trans('app.dashboard.catalog.product.index.controller_name',[],'dashboard') ,
				'parentController' => $translator->trans('app.dashboard.catalog.product.index.parent_controller_name',[],'dashboard'),
				'parentControllerRoute' => 'app_dashboard_home_index',
				'parentControllerRouteParams' => [],
				'products' => $pagination,
				'filtersForm' => $filtersForm->createView(),
			]);
			
			
		}
		
		#[Route('/show/{id}', name: 'show',methods: ['GET'])]
		public function show(Product $product,TranslatorInterface $translator):Response
		{
			
			if(!$product){
				$this->addFlash('danger','app.dashboard.catalog.product.show.flash_danger');
				return $this->redirectToRoute('app_dashboard_catalog_product_index');
			}
			
			return $this->render('dashboard/Catalog/Product/show.html.twig', [
				'controller_name' => $translator->trans('app.dashboard.catalog.product.show.controller_name',[],'dashboard').$product->getName(),
				'parentController' => $translator->trans('app.dashboard.catalog.product.show.parent_controller_name',[],'dashboard'),
				'parentControllerRoute' => 'app_dashboard_catalog_Product_index',
				'parentControllerRouteParams' => [],
				'product' => $product,
			]);
		}
		
		#[Route('/new/step/1', name: 'new_step_1',methods: ['GET','POST'])]
		public function new(TranslatorInterface $translator):Response
		{
		
			$product = new Product();
			$product->setDateAdd(new \DateTime());
			$product->setDateUpdate(new \DateTime());
			$product->setAvailableDate(new \DateTime());
			
			
			$form = $this->createForm(WizardNewProductFormFirst::class,$product);
			$form->handleRequest($this->requestStack->getMainRequest());
			if($form->isSubmitted() && $form->isValid()){
				
				$this->entityManager->persist($product);
				$this->entityManager->flush();
				$this->addFlash('success','app.dashboard.catalog.product.new.flash_success');
				
				
				  return $this->redirectToRoute('app_dashboard_catalog_Product_new_step_2',[
					'id' => $product->getId(),
				]);
				
			}
			
			return $this->render('dashboard/Catalog/Product/_form_wizard_step_1.html.twig', [
				'controller_name' => $translator->trans('app.dashboard.catalog.product.new.controller_name',[],'dashboard'),
				'parentController' => $translator->trans('app.dashboard.catalog.product.new.parent_controller_name',[],'dashboard'),
				'parentControllerRoute' => 'app_dashboard_catalog_Product_index',
				'parentControllerRouteParams' => [],
				'form' => $form->createView(),
				'product' => $product,
			]);
		}
		
		#[Route('/new/step/2/{id}', name: 'new_step_2',methods: ['GET','POST'])]
		public function newStep2(Product $product, TranslatorInterface $translator):Response
		{
			if(!$product){
				$this->addFlash('danger','app.dashboard.catalog.product.new.flash_danger');
				return $this->redirectToRoute('app_dashboard_catalog_product_index');
			}
			$form_1 = $this->createForm(WizardNewProductFormFirst::class,$product);
			$form_1->handleRequest($this->requestStack->getMainRequest());
			if($form_1->isSubmitted() && $form_1->isValid()){
				
				$this->entityManager->persist($product);
				$this->entityManager->flush();
				$this->addFlash('success','app.dashboard.catalog.product.new.flash_success');
				
				return $this->redirectToRoute('app_dashboard_catalog_Product_new_step_2',[
					'id' => $product->getId(),
				]);
				
			}
			
			$form_Images = $this->createForm(WizardNewProductFormSecond::class,$product);
			$form_Images->handleRequest($this->requestStack->getMainRequest());
			if($form_Images->isSubmitted() && $form_Images->isValid()){
				
				$this->entityManager->persist($product);
				$this->entityManager->flush();
				$this->addFlash('success','app.dashboard.catalog.product.new.flash_success');
				
				return $this->redirectToRoute('app_dashboard_catalog_Product_new_step_2',[
					'id' => $product->getId(),
				]);
				
			}
			
			$form_Prix = $this->createForm(WizardNewProductFormPrix::class,$product);
			$form_Prix->handleRequest($this->requestStack->getMainRequest());
			if($form_Prix->isSubmitted() && $form_Prix->isValid()){
				
				$this->entityManager->persist($product);
				$this->entityManager->flush();
				$this->addFlash('success','app.dashboard.catalog.product.new.flash_success');
				
				return $this->redirectToRoute('app_dashboard_catalog_Product_new_step_2',[
					'id' => $product->getId(),
				]);
				
			}
			
			
			
			
			
			return $this->render('dashboard/Catalog/Product/new.html.twig', [
				'controller_name' => $translator->trans('app.dashboard.catalog.product.new.controller_name',[],'dashboard'),
				'parentController' => $translator->trans('app.dashboard.catalog.product.new.parent_controller_name',[],'dashboard'),
				'parentControllerRoute' => 'app_dashboard_catalog_Product_index',
				'parentControllerRouteParams' => [],
				'form' => $form_1->createView(),
				'form_Images' => $form_Images->createView(),
				'form_Prix' => $form_Prix->createView(),
				'product' => $product,
				'warehouses'=>$this->entityManager->getRepository(Warehouse::class)->findAll(),
			]);
		}
		
		#[Route('/delete/{id}', name: 'delete',methods: ['GET','POST'])]
		public function delete(Product $product, TranslatorInterface $translator):Response
		{
			if(!$product){
				$this->addFlash('danger','app.dashboard.catalog.product.delete.flash_danger');
				return $this->redirectToRoute('app_dashboard_catalog_product_index');
			}
			$product->setIsDeleted(true);
			$this->entityManager->persist($product);
			$this->entityManager->flush();
			$this->addFlash('success','app.dashboard.catalog.product.delete.flash_success');
			
			return $this->redirectToRoute('app_dashboard_catalog_product_index');
		}
		#[Route('/generate/combinations/{id}', name: 'generate_combinations',methods: ['GET','POST'])]
		public function generateCombinations(Product $product, TranslatorInterface $translator):Response
		{
			$referer = $this->requestStack->getMainRequest()->headers->get('referer');
			dd($this->requestStack->getMainRequest());
			if(!$product){
				$this->addFlash('danger','app.dashboard.catalog.product.generate_combinations.flash_danger');
				return $this->redirectToRoute('app_dashboard_catalog_product_index');
			}
			
			
			return $this->redirectToRoute('app_dashboard_catalog_product_index');
		}
		
		#[Route('/unpublish/{id}', name: 'unpublish',methods: ['GET','POST'])]
		public function unpublish(Product $product, TranslatorInterface $translator):Response
		{
			if(!$product){
				$this->addFlash('danger','app.dashboard.catalog.product.unpublish.flash_danger');
				return $this->redirectToRoute('app_dashboard_catalog_product_index');
			}
			$product->setIsActive(false);
			$this->entityManager->persist($product);
			$this->entityManager->flush();
			$this->addFlash('success','app.dashboard.catalog.product.unpublish.flash_success');
			
			return $this->redirectToRoute('app_dashboard_catalog_product_index');
		}
		#[Route('/publish/{id}', name: 'publish',methods: ['GET','POST'])]
		public function publish(Product $product, TranslatorInterface $translator):Response
		{
			if(!$product){
				$this->addFlash('danger','app.dashboard.catalog.product.publish.flash_danger');
				return $this->redirectToRoute('app_dashboard_catalog_product_index');
			}
			$product->setIsActive(true);
			$this->entityManager->persist($product);
			$this->entityManager->flush();
			$this->addFlash('success','app.dashboard.catalog.product.publish.flash_success');
			
			return $this->redirectToRoute('app_dashboard_catalog_product_index');
		}
		
		#[Route('/restore/{id}', name: 'restore',methods: ['GET','POST'])]
		public function restore(Product $product, TranslatorInterface $translator):Response
		{
			if(!$product){
				$this->addFlash('danger','app.dashboard.catalog.product.restore.flash_danger');
				return $this->redirectToRoute('app_dashboard_catalog_product_index');
			}
			$product->setIsDeleted(false);
			$this->entityManager->persist($product);
			$this->entityManager->flush();
			$this->addFlash('success','app.dashboard.catalog.product.restore.flash_success');
			
			return $this->redirectToRoute('app_dashboard_catalog_product_index');
		}
		
		#[Route('/edit/{id}', name: 'edit',methods: ['GET','POST'])]
		public function edit(Product $product, TranslatorInterface $translator):Response
		{
			return $this->render('dashboard/Catalog/Product/edit.html.twig', [
				'controller_name' => $translator->trans('app.dashboard.catalog.product.edit.controller_name',[],'dashboard').' '.$product->getName(),
				'parentController' => $translator->trans('app.dashboard.catalog.product.edit.parent_controller_name',[],'dashboard'),
				'parentControllerRoute' => 'app_dashboard_catalog_Product_product',
				'parentControllerRouteParams' => [
					'id' => $product->getId(),
				],
			]);
		}
	
		
		
		public function productsIndexQueryBuilder($query,array $Options)
		{
			$query->andWhere('p.isActive = :isActive')
				->setParameter('isActive',true);
			
			
			return $query;
		}
		public function handleIndexFiltersForm($filters,$query)
		{
			
			if($filters['name']){
				$query->andWhere('p.name LIKE :name')
					->setParameter('name','%'.$filters['name'].'%');
			}
			if($filters['category']){
				$query->andWhere('p.mainCategory = :category')
					->setParameter('category',$filters['category']);
			}
			if($filters['isActive']){
				$query->andWhere('p.isActive = :isActive')
					->setParameter('isActive',$filters['isActive']);
			}
			if($filters['isDeleted']){
				$query->andWhere('p.isDeleted = :isDeleted')
					->setParameter('isDeleted',$filters['isDeleted']);
			}
			if($filters['sku']){
				$query->andWhere('p.sku LIKE :sku')
					->setParameter('sku','%'.$filters['sku'].'%');
			}
			$query->orderBy('p.id', 'ASC');
			$query = $query->getQuery();
			return $query;
		}
		
		
	}