<?php

namespace App\Controller\Dashboard\config;

use App\Entity\Wilaya;
use App\Form\Dashboard\config\WilayaType;
use App\Repository\WilayaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('dashboard/config/wilaya', name: 'app_dashboard_wilaya_')]
class WilayaController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET','POST'])]
    public function index(WilayaRepository $wilayaRepository,EntityManagerInterface $entityManager,Request $request): Response
    {
	    $wilaya = new Wilaya();
	    $form = $this->createForm(WilayaType::class, $wilaya);
	    $form->handleRequest($request);
	    
	    if ($form->isSubmitted() && $form->isValid()) {
		    $entityManager->persist($wilaya);
		    $entityManager->flush();
		    return $this->redirectToRoute('app_dashboard_wilaya_index', [], Response::HTTP_SEE_OTHER);
	    }
        return $this->render('dashboard/config/wilaya/index.html.twig', [
            'wilayas' => $wilayaRepository->findAll(),
	        'form' => $form,
	        'parentController'=>'Configuration Wilayas',
	        'controller_name' => 'Wilayas Index',
	        'pageSubTitle' => 'List of all Wilayas',
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $wilaya = new Wilaya();
        $form = $this->createForm(WilayaType::class, $wilaya);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($wilaya);
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_wilaya_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/config/wilaya/new.html.twig', [
            'wilaya' => $wilaya,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Wilaya $wilaya): Response
    {
        return $this->render('dashboard/config/wilaya/show.html.twig', [
            'wilaya' => $wilaya,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Wilaya $wilaya, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WilayaType::class, $wilaya);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_wilaya_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/config/wilaya/edit.html.twig', [
            'wilaya' => $wilaya,
            'form' => $form,
	        'parentController'=>'Configuration Wilayas',
	        'controller_name' => 'Edit Wilaya',
	        'pageSubTitle' => 'List of all Wilayas',
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Wilaya $wilaya, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$wilaya->getId(), $request->request->get('_token'))) {
            $entityManager->remove($wilaya);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dashboard_wilaya_index', [], Response::HTTP_SEE_OTHER);
    }
}
