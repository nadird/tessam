<?php

namespace App\Controller\Dashboard\webmanagement;

use App\Entity\WebMenu;
use App\Form\Dashboard\webconfig\WebMenuType;
use App\Repository\WebMenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard/web/menu', name: 'app_dashboard_web_menu_')]
class WebMenuController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(WebMenuRepository $webMenuRepository): Response
    {
        return $this->render('dashboard/webmanagement/web_menu/index.html.twig', [
            'web_menus' => $webMenuRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $webMenu = new WebMenu();
        $form = $this->createForm(WebMenuType::class, $webMenu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($webMenu);
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_web_menu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/webmanagement/web_menu/new.html.twig', [
            'web_menu' => $webMenu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(WebMenu $webMenu): Response
    {
        return $this->render('dashboard/webmanagement/web_menu/show.html.twig', [
            'web_menu' => $webMenu,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, WebMenu $webMenu, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WebMenuType::class, $webMenu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_web_menu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/webmanagement/web_menu/edit.html.twig', [
            'web_menu' => $webMenu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, WebMenu $webMenu, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$webMenu->getId(), $request->request->get('_token'))) {
            $entityManager->remove($webMenu);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dashboard_web_menu_index', [], Response::HTTP_SEE_OTHER);
    }
}
