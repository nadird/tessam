<?php

namespace App\Controller\Dashboard\config;

use App\Entity\EmplRole;
use App\Form\Dashboard\config\EmplRoleType;
use App\Repository\EmplRoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard/config/users/roles', name: 'dashboard_config_users_roles_')]
class EmplRoleController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET', 'POST'])]
    public function index(EmplRoleRepository $emplRoleRepository,Request $request, EntityManagerInterface $entityManager): Response
    {
	    $emplRole = new EmplRole();
	    $form = $this->createForm(EmplRoleType::class, $emplRole);
	    $form->handleRequest($request);
	    
	    if ($form->isSubmitted() && $form->isValid()) {
		    $entityManager->persist($emplRole);
		    $entityManager->flush();
		    
		    return $this->redirectToRoute('dashboard_config_users_roles_index', [], Response::HTTP_SEE_OTHER);
	    }
        return $this->render('dashboard/config/empl_role/index.html.twig', [
            'empl_roles' => $emplRoleRepository->findAll(),
	        'form' => $form,
	        'parentController'=>'Users Config',
	        'controller_name'=>'Roles',
	        'pageSubTitle' => 'List of all Roles',
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $emplRole = new EmplRole();
        $form = $this->createForm(EmplRoleType::class, $emplRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($emplRole);
            $entityManager->flush();

            return $this->redirectToRoute('dashboard_config_users_roles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/config/empl_role/new.html.twig', [
            'empl_role' => $emplRole,
            'form' => $form,
	        'parentController'=>'Users Config',
	        'Controller_name'=>'New role',
	        'pageSubTitle' => 'Edit Roles',
        ]);
    }

    #[Route('edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EmplRole $emplRole, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EmplRoleType::class, $emplRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('dashboard_config_users_roles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/config/empl_role/edit.html.twig', [
            'empl_role' => $emplRole,
            'form' => $form,
	        'parentController'=>'Users Config',
	        'Controller_name'=>'Roles',
	        'pageSubTitle' => 'List of all Roles',
        ]);
    }

    #[Route('delete/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, EmplRole $emplRole, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$emplRole->getId(), $request->request->get('_token'))) {
            $entityManager->remove($emplRole);
            $entityManager->flush();
        }

        return $this->redirectToRoute('dashboard_config_users_roles_index', [], Response::HTTP_SEE_OTHER);
    }
}
