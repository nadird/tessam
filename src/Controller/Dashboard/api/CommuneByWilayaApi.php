<?php
	namespace App\Controller\Dashboard\api;
	
	
	
	
	
	
	use App\Entity\Commune;
	use App\Entity\Wilaya;
	use App\Repository\CbienRepository;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\JsonResponse;
	use Symfony\Component\HttpFoundation\RequestStack;
	use Symfony\Component\Routing\Annotation\Route;
	
	#[Route('/public/api/addresses', name: 'app_public_api_address')]
	class CommuneByWilayaApi extends AbstractController
	{
		public function __construct(
			private EntityManagerInterface $entityManager,
			private RequestStack $requestStack,
		){
		}
		#[Route('/commune/wilaya/{id}', name: 'get_communes',methods: ['GET'])]
		public function getCommunesByWilaya($id):JsonResponse
		{
			$wilaya=$this->entityManager->getRepository(Wilaya::class)->findOneById($id);
			$communes=$this->entityManager->getRepository(Commune::class)->findByWilaya($wilaya);
			$toSend=[];
			foreach ($communes as $commune){
				$toSend[]=[
					'id'=>$commune->getId(),
					'name'=>$commune->getName(),
				];
			}
			if(!$communes){
				return new JsonResponse(['error'=>'Not Found'],404);
			}
			return new JsonResponse([$toSend]);
		}
	}