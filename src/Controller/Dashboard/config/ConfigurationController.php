<?php

namespace App\Controller\Dashboard\config;

use App\Entity\Configuration;
use App\Form\Dashboard\config\ConfigurationType;
use App\Repository\ConfigurationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('dashboard/config/configurationKeys', name: 'app_dashboard_config_configurationKeys_')]
class ConfigurationController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET', 'POST'])]
    public function index(ConfigurationRepository $configurationRepository,Request $request, EntityManagerInterface $entityManager): Response
    {
	    $configuration = new Configuration();
	    $form = $this->createForm(ConfigurationType::class, $configuration);
	    $form->handleRequest($request);
	    
	    if ($form->isSubmitted() && $form->isValid()) {
		    $entityManager->persist($configuration);
		    $entityManager->flush();
		    
		    return $this->redirectToRoute('app_dashboard_config_configurationKeys_index', [], Response::HTTP_SEE_OTHER);
	    }
        return $this->render('dashboard/config/configuration_keys/index.html.twig', [
            'configurations' => $configurationRepository->findAll(),
	        'form'=>$form,
	        'parentController'=>'Configuration',
	        'controller_name' => 'Configuration Keys',
	        'pageSubTitle' => 'Configuration Keys',
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $configuration = new Configuration();
        $form = $this->createForm(ConfigurationType::class, $configuration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($configuration);
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_config_configurationKeys_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/config/configuration_keys/new.html.twig', [
            'configuration' => $configuration,
            'form' => $form,
	        'parentController'=>'Configuration',
	        'controller_name' => 'Configuration Keys',
	        'pageSubTitle' => 'Configuration Keys',
        ]);
    }
	

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Configuration $configuration, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ConfigurationType::class, $configuration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_config_configurationKeys_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/config/configuration_keys/edit.html.twig', [
            'configuration' => $configuration,
            'form' => $form,
	        'parentController'=>'Configuration',
	        'controller_name' => 'Configuration Keys',
	        'pageSubTitle' => 'Configuration Keys',
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['POST', 'DELETE', 'GET'])]
    public function delete(
		Request $request, Configuration $configuration,
		
		EntityManagerInterface $entityManager): Response
    {
	    $entityManager->remove($configuration);
	    $entityManager->flush();
        $this->addFlash('success', 'Configuration deleted successfully');

        return $this->redirectToRoute('app_dashboard_config_configurationKeys_index', [], Response::HTTP_SEE_OTHER);
    }
}
