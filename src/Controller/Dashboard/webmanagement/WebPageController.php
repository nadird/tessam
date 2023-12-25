<?php

namespace App\Controller\Dashboard\webmanagement;

use App\Entity\WebPage;
use App\Form\Dashboard\webconfig\WebPageType;
use App\Repository\WebPageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard/web/page', name: 'app_dashboard_web_page_')]

class WebPageController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(WebPageRepository $webPageRepository): Response
    {
        return $this->render('dashboard/web_page/index.html.twig', [
            'web_pages' => $webPageRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $webPage = new WebPage();
        $form = $this->createForm(WebPageType::class, $webPage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($webPage);
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_web_page_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/web_page/new.html.twig', [
            'web_page' => $webPage,
            'form' => $form,
        ]);
    }
	

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, WebPage $webPage, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WebPageType::class, $webPage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_web_page_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/web_page/edit.html.twig', [
            'web_page' => $webPage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, WebPage $webPage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$webPage->getId(), $request->request->get('_token'))) {
            $entityManager->remove($webPage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dashboard_web_page_index', [], Response::HTTP_SEE_OTHER);
    }
}
