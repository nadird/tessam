<?php
	namespace App\Controller\Dashboard\api;
	
	
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\RequestStack;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;
	
	#[Route('/public/document/attestations', name: 'app_public_api_documents_')]
	class DocumentsApi extends AbstractController
	{
		public function __construct(
			private EntityManagerInterface $entityManager,
			private RequestStack $requestStack,
		){
		}
		#[Route('/document/order/reservation/{uuid}', name: 'attestation_reservation',methods: ['GET'])]
		public function getAttestations($uuid):Response
		{
			$order = $this->entityManager->getRepository(\App\Entity\Order::class)->findOneBy(['uuid'=>$uuid]);
			if(!$order){
				return new Response('Not Found',404);
			}
			$attestation = $order->getAttestationReservation();
			if(!$attestation){
				return new Response('Not Found',404);
			}
			$path = $this->getParameter('attestations_directory').'/'.$attestation;
			$content = file_get_contents($path);
			$response = new Response();
			$response->headers->set('Content-Type', 'application/pdf');
			$response->setContent($content);
			return $response;
			
			
			
		}
		#[Route('/document/order/versement/{uid}', name: 'order_odv',methods: ['GET'])]
		public function getPaiementOrder($uid):Response
		{
			$paiement = $this->entityManager->getRepository(\App\Entity\PaiementHistory::class)->findOneBy(['uid'=>$uid]);
			if(!$paiement){
				return new Response('Not Found',404);
			}
			$attestation = $paiement->getOdv();
			if(!$attestation){
				return new Response('Not Found',404);
			}
			$path = $this->getParameter('attestations_directory').'/'.$attestation;
			$content = file_get_contents($path);
			$response = new Response();
			$response->headers->set('Content-Type', 'application/pdf');
			$response->setContent($content);
			return $response;
			
		}
		#[Route('/document/order/rcpc/{uid}', name: 'order_rcpc',methods: ['GET'])]
		public function getPaiementRecipt($uid):Response
		{
			$paiement = $this->entityManager->getRepository(\App\Entity\PaiementHistory::class)->findOneBy(['uid'=>$uid]);
			if(!$paiement){
				return new Response('Not Found',404);
			}
			$attestation = $paiement->getRcpc();
			if(!$attestation){
				return new Response('Not Found',404);
			}
			$path = $this->getParameter('attestations_directory').'/'.$attestation;
			$content = file_get_contents($path);
			$response = new Response();
			$response->headers->set('Content-Type', 'application/pdf');
			$response->setContent($content);
			return $response;
			
		}
		#[Route('/document/order/recap/{uuid}', name: 'order_recap',methods: ['GET'])]
		public function getRecapOrder($uuid):Response
		{
			$order = $this->entityManager->getRepository(\App\Entity\Order::class)->findOneBy(['uuid'=>$uuid]);
			if(!$order){
				return new Response('Not Found',404);
			}
			$attestation = $order->getAttestationReservation();
			if(!$attestation){
				return new Response('Not Found',404);
			}
			$path = $this->getParameter('attestations_directory').'/'.$attestation;
			$content = file_get_contents($path);
			$response = new Response();
			$response->headers->set('Content-Type', 'application/pdf');
			$response->setContent($content);
			return $response;
			
			
			
		}
	}