<?php
	namespace App\Controller\Dashboard\api;
	
	
	
	
	
	
	use App\Entity\TraficTracking;
	use App\Repository\CbienRepository;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Cookie;
	use Symfony\Component\HttpFoundation\JsonResponse;
	use Symfony\Component\HttpFoundation\RequestStack;
	use Symfony\Component\Routing\Annotation\Route;
	
	#[Route('internal/api/changeevssid', name: 'app_public_api_')]
	class changeEvssid extends AbstractController
	{
		public function __construct(
			private EntityManagerInterface $entityManager,
			private RequestStack $requestStack,

		){
		}
		#[Route('/{oldSsid}/{newSsid}', name: 'change_evssid',methods: ['GET'])]
		public function getFinalPrice($oldSsid,$newSsid):JsonResponse
		{
			$request = $this->requestStack->getMainRequest();
			$evssid = $request->cookies->get('EVSSID');
			$tracking= $this->entityManager->getRepository(TraficTracking::class)->findByEvssid($newSsid);
			for($i=0;$i<count($tracking);$i++){
				$tracking[$i]->setEvssid($oldSsid);
				$this->entityManager->persist($tracking[$i]);
				$this->entityManager->flush();
			}
			$request->cookies->set('EVSSID',$oldSsid);
			$response = new JsonResponse();
			$cookie= new Cookie('EVSSID',$oldSsid,time()+(3600*24*365));
			$response->headers->setCookie($cookie);
			$response->setData([
				'status' => 'success',
				'message' => 'evssid changed successfully',
			]);
			return $response;
			
			
		}
	}