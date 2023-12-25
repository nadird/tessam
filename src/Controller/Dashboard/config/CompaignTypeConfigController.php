<?php

namespace App\Controller\Dashboard\config;

use App\Entity\CompaignType;
use App\Form\Dashboard\config\CompaignTypeType;
use App\Repository\CompaignTypeRepository;
use App\Service\ImageUploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('dashboard/config/configcompaignType', name: 'app_dashboard_config_compaignType_')]
class CompaignTypeConfigController extends AbstractController
{
	public function __construct(
		private ImageUploadService $imageUploadService
	)
	{
	}
	
	#[Route('/', name: 'index', methods: ['GET', 'POST'])]
    public function index(CompaignTypeRepository $compaignTypeRepository,Request $request, EntityManagerInterface $entityManager): Response
    {
	    $compaignType = new CompaignType();
	    $form = $this->createForm(CompaignTypeType::class, $compaignType);
	    $form->handleRequest($request);
	    
	    if ($form->isSubmitted() && $form->isValid()) {
			if($form->get('logo')->getData()){
				$compaignType->setLogo($this->imageUploadService->uploadImage($form->get('logo')->getData(),$this->getParameter('public_directory')));
			}
		    $entityManager->persist($compaignType);
		    $entityManager->flush();
		    
		    return $this->redirectToRoute('app_dashboard_config_compaignType_index', [], Response::HTTP_SEE_OTHER);
	    }
        return $this->render('dashboard/config/compaign_type_config/index.html.twig', [
            'compaign_types' => $compaignTypeRepository->findAll(),
	        'form' => $form->createView(),
	        'controller_name' => 'CompaignTypeConfigController',
	        'parentController' => 'ConfigController',
	        
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $compaignType = new CompaignType();
        $form = $this->createForm(CompaignTypeType::class, $compaignType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
	        if($form->get('logo')->getData()){
		        $compaignType->setLogo($this->imageUploadService->uploadImage($form->get('logo')->getData(),$this->getParameter('public_directory')));
	        }
            $entityManager->persist($compaignType);
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_config_compaignType_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/config/compaign_type_config/new.html.twig', [
            'compaign_type' => $compaignType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(CompaignType $compaignType): Response
    {
        return $this->render('dashboard/config/compaign_type_config/show.html.twig', [
            'compaign_type' => $compaignType,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CompaignType $compaignType, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CompaignTypeType::class, $compaignType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
	        if($form->get('logo')->getData()){
		        $compaignType->setLogo($this->imageUploadService->uploadImage($form->get('logo')->getData(),$this->getParameter('public_directory')));
	        }
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_config_compaignType_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/config/compaign_type_config/edit.html.twig', [
            'compaign_type' => $compaignType,
			'controller_name' => 'CompaignTypeConfigController',
			'parentController' => 'ConfigController',
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['POST','DELETE','GET'])]
    public function delete(Request $request, CompaignType $compaignType, EntityManagerInterface $entityManager): Response
    {
	    $entityManager->remove($compaignType);
			    $entityManager->flush();
        if ($this->isCsrfTokenValid('delete'.$compaignType->getId(), $request->request->get('_token'))) {
            $entityManager->remove($compaignType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dashboard_config_compaignType_index', [], Response::HTTP_SEE_OTHER);
    }
}
