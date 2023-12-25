<?php
	
	namespace App\Service;
	
	use Symfony\Component\String\Slugger\SluggerInterface;
	
	class AlbumUploadService
	{
		public function __construct(private SluggerInterface $slugger)
		{
		}
		
		public function uploadAlbumImage($image, $directory,$albumName)
		{
			$originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
			$safeAlbumname = $this->slugger->slug($albumName);
			$newFilename = $safeAlbumname.'_'.uniqid().'.'.$image->guessExtension();
			$image->move(
				$directory.'/'.str_replace(' ','_',$safeAlbumname).'/',
				$newFilename
			);
			
			return $newFilename;
		}
		
	}
