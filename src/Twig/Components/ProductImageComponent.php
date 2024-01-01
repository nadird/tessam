<?php
	namespace App\Twig\Components;
	
	
	use App\Entity\Image;
	use App\Entity\Product;
	use App\Repository\ImageResizedRepository;
	use App\Repository\ImageTypeRepository;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
	
	
	#[AsTwigComponent]
	class ProductImageComponent extends AbstractController
	{
		
		public Image $image;
		public string $size;
		public product $product;
		
		public static function getComponentName(): string
		{
			return 'product_images_component';
		}
		public function __construct(
			
			//private ProductRepository $productRepository,
			private ImageResizedRepository $imageResizedRepository,
			private ImageTypeRepository $imageTypeRepository,
			
		)
		{
		
		
		}
		public function getProductImageBySize($image,$size)
		{
			$size = $this->imageTypeRepository->findOneBy(['name'=>$size]);
			return  $this->imageResizedRepository->findOneBy(['image'=>$image,'type'=>$size]);
			
		}
		
		
		
		// Add any additional methods you might need
	}
