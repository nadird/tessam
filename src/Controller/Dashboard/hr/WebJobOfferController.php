<?php

namespace App\Controller\Dashboard\hr;

use App\Entity\WebJobApplication;
use App\Entity\WebJobOffer;
use App\Form\Dashboard\webconfig\WebJobOfferType;
use App\Repository\WebJobOfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard/hr/job/offer', name: 'app_dashboard_hr_job_offer_')]
class WebJobOfferController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(WebJobOfferRepository $webJobOfferRepository): Response
    {
        return $this->render('dashboard/hr/jobOffer/index.html.twig', [
            'web_job_offers' => $webJobOfferRepository->findAll(),
	        'controller_name' => "Offres d'emploi",
	        'parentController' => 'HR',
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $webJobOffer = new WebJobOffer();
        $form = $this->createForm(WebJobOfferType::class, $webJobOffer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			$webJobOffer->setCreatedAt(new \DateTime());
            $entityManager->persist($webJobOffer);
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_hr_job_offer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/hr/jobOffer/new.html.twig', [
            'web_job_offer' => $webJobOffer,
            'form' => $form,
        ]);
    }
	
	#[Route('/jobapplications/{id}', name: 'show_applications', methods: ['GET'])]
	public function applications(WebJobOffer $webJobOffer): Response
	{
		$applications = $webJobOffer->getWebJobApplications();
		
		return $this->render('dashboard/hr/jobOffer/applications.html.twig', [
			'web_job_offer' => $webJobOffer,
			'applications' => $applications
		]);
	}
	#[Route('/jobapplication/{id}', name: 'single_application', methods: ['GET'])]
	public function application($id,EntityManagerInterface $entityManager): Response
	{
		$application = $entityManager->getRepository(WebJobApplication::class)->findOneById($id);
		
		return $this->render('dashboard/hr/jobOffer/application.html.twig', [
			'applicant' => $application,
		]);
	}
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(WebJobOffer $webJobOffer): Response
    {
        return $this->render('dashboard/hr/jobOffer/show.html.twig', [
            'web_job_offer' => $webJobOffer,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, WebJobOffer $webJobOffer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WebJobOfferType::class, $webJobOffer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_hr_job_offer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/hr/jobOffer/edit.html.twig', [
            'web_job_offer' => $webJobOffer,
            'form' => $form,
	        'controller_name' => "Offres d'emploi",
	        'parentController' => 'HR',
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, WebJobOffer $webJobOffer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$webJobOffer->getId(), $request->request->get('_token'))) {
            $entityManager->remove($webJobOffer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dashboard_hr_job_offer_index', [], Response::HTTP_SEE_OTHER);
    }
}
