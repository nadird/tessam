<?php
	
	namespace App\Service;
	
	use Symfony\Component\String\Slugger\SluggerInterface;
	
	class ImageUploadService
	{
		public function __construct(private SluggerInterface $slugger)
		{
		}
		
		public function uploadImage($image, $directory)
		{
			$originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
			$safeFilename = $this->slugger->slug($originalFilename);
			$newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();
			$image->move(
				$directory,
				$newFilename
			);
			
			return $newFilename;
		}
		public function resizeuploadImage($image, $directory,$size)
		{
			list($targetWidth, $targetHeight) = $size;
			
			$originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
			$safeFilename = $this->slugger->slug($originalFilename);
			$newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();
			$temporaryPath = $image->move($directory, $newFilename)->getPathname();
			
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
		
		
		public function uploadCv($image, $directory)
		{
			$originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
			$safeFilename = $this->slugger->slug($originalFilename).'CV';
			$newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();
			$image->move(
				$directory,
				$newFilename
			);
			
			return $newFilename;
		}
		
	}
