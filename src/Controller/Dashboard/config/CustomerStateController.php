<?php

namespace App\Controller\Dashboard\config;

use App\Entity\CustomerState;
use App\Form\Dashboard\config\CustomerStateType;
use App\Repository\CustomerStateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('dashboard/config/customerState', name: 'app_dashboard_config_customerState_')]
class CustomerStateController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET', 'POST'])]
    public function index(CustomerStateRepository $customerStateRepository,Request $request, EntityManagerInterface $entityManager): Response
    {
	    $customerState = new CustomerState();
	    $form = $this->createForm(CustomerStateType::class, $customerState);
	    $form->handleRequest($request);
	    
	    if ($form->isSubmitted() && $form->isValid()) {
		    $entityManager->persist($customerState);
		    $entityManager->flush();
		    
		    return $this->redirectToRoute('app_dashboard_config_customerState_index', [], Response::HTTP_SEE_OTHER);
	    }
		
        return $this->render('dashboard/config/customer_state/index.html.twig', [
            'customer_states' => $customerStateRepository->findAll(),
	        'form'=>$form,
	        'parentController'=>'Configuration',
	        'controller_name' => 'Customer State Index',
	        'pageSubTitle' => 'All Customer States',
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $customerState = new CustomerState();
        $form = $this->createForm(CustomerStateType::class, $customerState);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($customerState);
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_config_customerState_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/config/customer_state/new.html.twig', [
            'customer_state' => $customerState,
            'form' => $form,
	        'parentController'=>'Configuration',
	        'controller_name' => 'New Customer State ',
	        'pageSubTitle' => 'Create New Customer State',
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(CustomerState $customerState): Response
    {
        return $this->render('dashboard/config/customer_state/show.html.twig', [
            'customer_state' => $customerState,
	        'parentController'=>'Configuration',
	        'controller_name' => 'Customer State Show',
	        'pageSubTitle' => 'Show'.$customerState->getName(),
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CustomerState $customerState, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CustomerStateType::class, $customerState);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_config_customerState_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/config/customer_state/edit.html.twig', [
            'customer_state' => $customerState,
            'form' => $form,
	        'parentController'=>'Configuration',
	        'controller_name' => 'Customer Edit '.$customerState->getName(),
	        'pageSubTitle' => 'Edit Customer State',
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, CustomerState $customerState, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$customerState->getId(), $request->request->get('_token'))) {
            $entityManager->remove($customerState);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dashboard_config_customerState_index', [], Response::HTTP_SEE_OTHER);
    }
}
