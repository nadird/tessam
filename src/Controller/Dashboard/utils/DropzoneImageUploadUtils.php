<?php
	namespace App\Controller\Dashboard\utils;
	
	use App\Entity\Image;
	use App\Entity\ImageResized;
	use App\Entity\ImageType;
	use App\Service\ImageUploadService;
	use Doctrine\ORM\EntityManagerInterface;
	use Psr\Log\LoggerInterface;
	use Symfony\Bridge\Twig\Mime\TemplatedEmail;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\File\File;
	use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
	use Symfony\Component\Mailer\MailerInterface;
	use Symfony\Component\Mime\Email;
	use Symfony\Component\Routing\Annotation\Route;
	use App\Entity\Product;
	use App\Form\Dashboard\Catalog\Product\NewProductFormType;
	use App\Form\Dashboard\Catalog\Product\ProductIndexFiltersFormType;
	use App\Form\Dashboard\Catalog\Product\wizard\WizardNewProductFormFirst;
	use App\Repository\ProductRepository;
	use Knp\Component\Pager\PaginatorInterface;
	use Symfony\Component\HttpFoundation\RequestStack;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\String\Slugger\SluggerInterface;
	use Symfony\Contracts\Translation\TranslatorInterface;
	use function MongoDB\BSON\toJSON;
	
	#[Route('dashboard/service/dropzone', name: 'app_dashboard_service_dropzone_', methods: ['POST','GET'])]
	class DropzoneImageUploadUtils extends AbstractController
	{
		public function __construct(
			private LoggerInterface $logger,
			private EntityManagerInterface $entityManager,
			private RequestStack $requestStack,
			private ImageUploadService $imageUploadService,
			private SluggerInterface $slugger,
		)
		{
		}
		#[Route('/product/upload/{id}', name: 'product_upload', methods: ['POST','GET'])]
		public function productImageUpload($id):Response
		{
			$product = $this->entityManager->getRepository(Product::class)->findOneById($id);
			$file = $this->requestStack->getMainRequest()->files->get('file');
			$size = $file->getSize();
			$mime = $file->getMimeType();
			
			$image = new Image();
			$image->setProduct($product);
			$image->setDateAdd(new \DateTime());
			$image->setIsDeleted(false);
			$image->setAlt('product ',$product->getName());
			$imageName = $file->getClientOriginalName();
			$image->setName($imageName);
			$image->setUrl($this->imageUploadService->uploadImage($file,$this->getParameter('product_directory').'/'.$product->getId()));
			$this->entityManager->persist($image);
			$this->entityManager->persist($product);
			$this->entityManager->flush();
			$this->logger->info('product image upload');
			$this->resizeImageProduct($image,$product);
			$responseArray = [
				'id' => $image->getId(),
				'name' => $imageName,
				'url' => $image->getUrl(),
				'removeUrl' => $this->generateUrl('app_dashboard_service_dropzone_product_remove',['id'=>$image->getId()]),
				'path' => $this->getParameter('app.website_url').'/uploads/product/'.$product->getId().'/'.$image->getUrl(),
				'alt' => $image->getAlt(),
				'size' => round( $size/1024,2).' KB',
			];
			return new Response(json_encode($responseArray),200);
		}
		#[Route('/product/remove/{id}', name: 'product_remove', methods: ['POST','GET'])]
		public function productImageRemove($id):Response
		{
			$image = $this->entityManager->getRepository(Image::class)->findOneById($id);
			$image->setIsDeleted(true);
			$resized =  $image->getResized();
			foreach ($resized as $resize){
				$image->removeResized($resize);
				$this->entityManager->remove($resize);
			}
			$this->entityManager->persist($image);
			$this->entityManager->flush();
			$this->logger->info('product image remove');
			return new Response('success',200);
		}
		
		
		
		
		public function resizeImageProduct($file,$product)
		{
			//dd($file);
			$types = $this->entityManager->getRepository(ImageType::class)->findByIsProduct(true);
			$image = $file->getUrl();
			$directory = $this->getParameter('product_directory').'/'.$product->getId();
			$imagePath = $directory.'/'.$image;
			$imageFile = new File($imagePath);
			
			foreach ($types as $type){
				$imageresized = new ImageResized();
				$imageresized->setType($type);
				$imageresized->setUrl(
					$this->resizeuploadImageProduct(
						$imageFile,
						$directory,
						[$type->getWidth(),$type->getHeight()],
						$product,
						$type
						
					)
				);
				$imageresized->setImage($file);
				$this->entityManager->persist($imageresized);
				$this->entityManager->persist($file);
			}
			$this->entityManager->flush();
		}
		public function resizeuploadImageProduct($image, $directory,$size,$product,$type)
		{
			list($targetWidth, $targetHeight) = $size;
			
			$originalFilename = pathinfo($image->getPathname(), PATHINFO_FILENAME);
			$safeFilename = $this->slugger->slug($product->getName().'-'.$type->getName());
			$newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();
			copy($image->getPathname(),$directory.'/'.$newFilename);
			$temporaryPath = $directory.'/'.$newFilename;
			
			$this->resizeAndCropImage($temporaryPath, $directory.'/'.$newFilename, $targetWidth, $targetHeight);
			
			return $newFilename;
		}
		private function resizeAndCropImage($path, $newPath, $targetWidth, $targetHeight)
		{
			// Create a new image resource from file
			$sourceImage = imagecreatefromstring(file_get_contents($path));
			if (!$sourceImage) {
				throw new \Exception('Could not create image resource from file.');
			}
			
			// Get the dimensions of the source image
			list($srcWidth, $srcHeight) = getimagesize($path);
			
			// Calculate aspect ratios
			$srcAspectRatio = $srcWidth / $srcHeight;
			$targetAspectRatio = $targetWidth / $targetHeight;
			
			// Initialize coordinates and dimensions for cropping
			$srcX = $srcY = 0;
			$newSrcWidth = $srcWidth;
			$newSrcHeight = $srcHeight;
			
			// Crop image to the desired aspect ratio
			if ($srcAspectRatio > $targetAspectRatio) {
				// Image is wider than the target aspect ratio
				$newSrcWidth = $srcHeight * $targetAspectRatio;
				$srcX = ($srcWidth - $newSrcWidth) / 2;
			} elseif ($srcAspectRatio < $targetAspectRatio) {
				// Image is taller than the target aspect ratio
				$newSrcHeight = $srcWidth / $targetAspectRatio;
				$srcY = ($srcHeight - $newSrcHeight) / 2;
			}
			
			// Create a new, blank image resource with the desired dimensions
			$destinationImage = imagecreatetruecolor($targetWidth, $targetHeight);
			
			// Copy and resize the source image to the destination image
			imagecopyresampled(
				$destinationImage,
				$sourceImage,
				0, 0,
				$srcX, $srcY, // Start at calculated x and y coordinates
				$targetWidth, $targetHeight,
				$newSrcWidth, $newSrcHeight // Use calculated width and height
			);
			
			// Save the resized and cropped image to the new path as a JPG
			imagejpeg($destinationImage, $newPath, 70); // Quality is 0-100, 90 is a good balance
			
			// Free up memory
			imagedestroy($sourceImage);
			imagedestroy($destinationImage);
		}
	}