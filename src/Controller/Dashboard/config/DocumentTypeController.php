<?php

namespace App\Controller\Dashboard\config;

use App\Entity\DocumentType;
use App\Form\Dashboard\config\DocumentTypeType;
use App\Repository\DocumentTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('dashboard/config/documentType', name: 'app_dashboard_config_documentType_')]
class DocumentTypeController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET', 'POST'])]
    public function index(DocumentTypeRepository $documentTypeRepository,Request $request, EntityManagerInterface $entityManager): Response
    {
	    $documentType = new DocumentType();
	    $form = $this->createForm(DocumentTypeType::class, $documentType);
	    $form->handleRequest($request);
	    
	    if ($form->isSubmitted() && $form->isValid()) {
		    $entityManager->persist($documentType);
		    $entityManager->flush();
		    
		    return $this->redirectToRoute('app_dashboard_config_documentType_index', [], Response::HTTP_SEE_OTHER);
	    }
	    
	    return $this->render('dashboard/config/document_type/index.html.twig', [
            'document_types' => $documentTypeRepository->findAll(),
		    'form'=>$form,
		    'parentController'=>'Configuration',
		    'controller_name' => 'Document Types Index',
		    'pageSubTitle' => 'All Document Types',
		    
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $documentType = new DocumentType();
        $form = $this->createForm(DocumentTypeType::class, $documentType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($documentType);
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_config_documentType_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/config/document_type/new.html.twig', [
            'document_type' => $documentType,
            'form' => $form,
	        'parentController'=>'Configuration',
	        'controller_name' => 'New Document Type',
	        'pageSubTitle' => 'Create a new document type',
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(DocumentType $documentType): Response
    {
        return $this->render('dashboard/config/document_type/show.html.twig', [
            'document_type' => $documentType,
	        'parentController'=>'Configuration',
	        'controller_name' => 'Show Document Type',
	        'pageSubTitle' => 'Document type'.$documentType->getName().' Details',
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DocumentType $documentType, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DocumentTypeType::class, $documentType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_config_documentType_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/config/document_type/edit.html.twig', [
            'document_type' => $documentType,
            'form' => $form,
	        'parentController'=>'Configuration',
	        'controller_name' => 'Edit Document Type',
	        'pageSubTitle' => 'Edit document type'.$documentType->getName().' Details',
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, DocumentType $documentType, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$documentType->getId(), $request->request->get('_token'))) {
            $entityManager->remove($documentType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dashboard_config_documentType_index', [], Response::HTTP_SEE_OTHER);
    }
}
