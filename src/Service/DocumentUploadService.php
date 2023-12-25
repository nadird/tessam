<?php
	
	namespace App\Service;
	
	use Symfony\Component\String\Slugger\SluggerInterface;
	
	class DocumentUploadService
	{
		public function __construct(
			private SluggerInterface $slugger,
			private \Doctrine\ORM\EntityManagerInterface $entityManager,
		){
		}
		
		public function uploadDocument($image, $directory)
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
		public function uploadDocumentCustomerForm($document,$uploadedFile,$customer,$lead,$directory,$employee){
			
			if ($uploadedFile) {
				$directory = $directory.'/'.$lead->getSlug().'/';
				$newFilename = $this->uploadDocument($uploadedFile,$directory );
				$document->setUrl($newFilename);
			}
			$document->setEmployee($employee);
			$document->setCustomer($customer);
			$document->setLead($lead);
			$document->setDateAdd(new \DateTime());
			$document->setIsDeleted(false);
			$this->entityManager->persist($document);
			$this->entityManager->flush();
			return true;
		}
		
	}
