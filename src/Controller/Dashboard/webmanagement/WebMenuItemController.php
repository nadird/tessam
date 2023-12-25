<?php

namespace App\Controller\Dashboard\webmanagement;

use App\Entity\WebMenuItem;
use App\Form\Dashboard\webconfig\WebMenuItemType;
use App\Repository\WebMenuItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard/webmanagement/menu/item', name: 'app_dashboard_webmanagement_menu_item_')]
class WebMenuItemController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(WebMenuItemRepository $webMenuItemRepository): Response
    {
        return $this->render('dashboard/webmanagement/web_menu_item/index.html.twig', [
            'web_menu_items' => $webMenuItemRepository->findAll(),
        ]);
    }
	#[Route('/menu/{id}', name: 'menu_index', methods: ['GET'])]
	public function menu($id, WebMenuItemRepository $webMenuItemRepository): Response
	{
		$webMenuItems = $webMenuItemRepository->findBy(['menu' => $id]);
		return $this->render('dashboard/webmanagement/web_menu_item/menu_items.html.twig', [
			'web_menu_items' => $webMenuItems,
		]);
	}

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $webMenuItem = new WebMenuItem();
        $form = $this->createForm(WebMenuItemType::class, $webMenuItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($webMenuItem);
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_webmanagement_menu_item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/webmanagement/web_menu_item/new.html.twig', [
            'web_menu_item' => $webMenuItem,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(WebMenuItem $webMenuItem): Response
    {
        return $this->render('dashboard/webmanagement/web_menu_item/show.html.twig', [
            'web_menu_item' => $webMenuItem,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, WebMenuItem $webMenuItem, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WebMenuItemType::class, $webMenuItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_webmanagement_menu_item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/webmanagement/web_menu_item/edit.html.twig', [
            'web_menu_item' => $webMenuItem,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, WebMenuItem $webMenuItem, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$webMenuItem->getId(), $request->request->get('_token'))) {
            $entityManager->remove($webMenuItem);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dashboard_webmanagement_menu_item_index', [], Response::HTTP_SEE_OTHER);
    }
}
