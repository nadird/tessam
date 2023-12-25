<?php

namespace App\Controller\Customer;

use App\Entity\Order;
use App\Entity\PaiementHistory;
use App\Entity\PaiementSchudle;
use App\Entity\Reduction;
use App\Form\Customer\CustomerEditType;
use App\Form\Customer\CustomerFinishRegistration;
use App\Service\ImageUploadService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/customer', name: 'app_customer_')]
#[isGranted('ROLE_CUSTOMER')]
class CustomerProfileController extends AbstractController
{
	public function __construct(
		private LoggerInterface $logger,
		private RequestStack $requestStack,
		private EntityManagerInterface $entityManager,
		private ImageUploadService $imageUploadService
		
	)
	{
	}
	
	#[Route('/', name: 'index')]
    public function index(): Response
    {
		$customer = $this->getUser();
		
        return $this->render('customer/customer_profile/index.html.twig', [
            'controller_name' => 'CustomerProfileController',
	        'customer' => $customer,
        ]);
    }
	#[Route('/orders', name: 'orders')]
	public function orders(): Response
	{
		$customer = $this->getUser();
		return $this->render('customer/customer_profile/orders.html.twig', [
			'controller_name' => 'CustomerProfileController',
			'customer' => $customer,
		]);
	}
	#[Route('/order/{id}', name: 'order')]
	public function order(Order $order): Response
	{
		$customer = $this->getUser();
		$reduction = $this->entityManager->getRepository(Reduction::class)->findOneBy(['commande' => $order]);
		if($order->getCustomer() != $customer){
			$this->addFlash('danger', 'Vous n\'avez pas le droit d\'accéder à cette page');
			$this->logger->info('Customer '.$customer->getId().' tried to access order '.$order->getId());
			return $this->redirectToRoute('app_customer_orders');
		}
		
		return $this->render('customer/customer_profile/order.html.twig', [
			'controller_name' => 'Order '.$order->getId(),
			'parentController' => 'Orders'.$order->getCustomer(),
			'order' => $order,
			'customer' => $customer,
			'reduction' => $reduction,
		]);
	}
	#[Route('/paiements/', name: 'paiements')]
	public function paiements(): Response
	{
		$customer = $this->getUser();
		return $this->render('customer/customer_profile/paiements.html.twig', [
			'controller_name' => 'CustomerProfileController',
			'customer' => $customer,
		]);
	}
	#[Route('/paiement/{id}', name: 'paiement')]
	public function paiement(PaiementHistory $paiementHistory): Response
	{
		$customer = $this->getUser();
		if($customer != $paiementHistory->getCustomer()){
			$this->addFlash('danger', 'Vous n\'avez pas le droit d\'accéder à cette page');
			$this->logger->info('Customer '.$customer->getId().' tried to access order '.$paiementHistory->getId());
			return $this->redirectToRoute('app_customer_orders');
		}
		return $this->render('customer/customer_profile/paiement.html.twig', [
			'controller_name' => 'CustomerProfileController',
			'customer' => $customer,
		]);
	}
	#[Route('/shcudles', name: 'schudles')]
	public function shcudles(): Response
	{
		$customer = $this->getUser();
		return $this->render('customer/customer_profile/schudles.html.twig', [
			'controller_name' => 'CustomerProfileController',
			'customer' => $customer,
		]);
	}
	#[Route('/shcudle/{id}', name: 'schudle')]
	public function shcudle(PaiementSchudle $paiementSchudle): Response
	{
		$customer = $this->getUser();
		if($customer != $paiementSchudle->getCustomer()){
			$this->addFlash('danger', 'Vous n\'avez pas le droit d\'accéder à cette page');
			$this->logger->info('Customer '.$customer->getId().' tried to access order '.$paiementSchudle->getId());
			return $this->redirectToRoute('app_customer_orders');
		}
		return $this->render('customer/customer_profile/schudle.html.twig', [
			'controller_name' => 'CustomerProfileController',
			'customer' => $customer,
		]);
	}
	#[Route('/profile', name: 'profile')]
	public function profile(): Response
	{
		$customer = $this->getUser();
		$form = $this->createForm(CustomerEditType::class, $customer);
		$form->handleRequest($this->requestStack->getMainRequest());
		if ($form->isSubmitted() && $form->isValid()) {
			$picture = $form->get('picture')->getData();
			if ($picture) {
				$pictureName = $this->imageUploadService->uploadImage($picture, $this->getParameter('customer_directory'));
				$customer->setPicture($pictureName);
			}
			$this->entityManager->persist($customer);
			$this->entityManager->flush();
			$this->addFlash('success', 'Votre profil a été mis à jour');
			return $this->redirectToRoute('app_customer_profile', ['id' => $customer->getId()]);
			
		}
		
		return $this->render('customer/customer_profile/profile.html.twig', [
			'controller_name' => 'CustomerProfileController',
			'customer' => $customer,
			'form' => $form->createView(),
			'parentController' => 'CustomerProfileController',
		]);
	}
	#[Route('/profile/edit/password', name: 'profile_edit_password')]
	public function profile_edit_password(): Response
	{
		$customer = $this->getUser();
		return $this->render('customer/customer_profile/profile_edit_password.html.twig', [
			'controller_name' => 'CustomerProfileController',
			'customer' => $customer,
		]);
	}
	
	
	
	
}
