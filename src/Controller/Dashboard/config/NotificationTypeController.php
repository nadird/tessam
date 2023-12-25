<?php

namespace App\Controller\Dashboard\config;

use App\Entity\NotificationType;
use App\Form\Dashboard\config\NotificationTypeType;
use App\Repository\NotificationTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('dashboard/config/notificationType', name: 'app_dashboard_config_notificationType_')]
class NotificationTypeController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET', 'POST'])]
    public function index(NotificationTypeRepository $notificationTypeRepository,Request $request, EntityManagerInterface $entityManager): Response
    {
	    $notificationType = new NotificationType();
	    $form = $this->createForm(NotificationTypeType::class, $notificationType);
	    $form->handleRequest($request);
	    
	    if ($form->isSubmitted() && $form->isValid()) {
		    $entityManager->persist($notificationType);
		    $entityManager->flush();
		    
		    return $this->redirectToRoute('app_dashboard_config_notificationType_index', [], Response::HTTP_SEE_OTHER);
	    }
        return $this->render('dashboard/config/notification_type/index.html.twig', [
            'notification_types' => $notificationTypeRepository->findAll(),
	        'form'=>$form->createView(),
	        'parentController'=>'Configuration',
	        'controller_name' => 'Notification Type Index',
	        'pageSubTitle' => 'All notifications types',
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $notificationType = new NotificationType();
        $form = $this->createForm(NotificationTypeType::class, $notificationType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($notificationType);
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_config_notificationType_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/config/notification_type/new.html.twig', [
            'notification_type' => $notificationType,
            'form' => $form,
	        'parentController'=>'Configuration',
	        'controller_name' => 'New notification type ',
	        'pageSubTitle' => 'new notifications types',
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(NotificationType $notificationType): Response
    {
        return $this->render('dashboard/config/notification_type/show.html.twig', [
            'notification_type' => $notificationType,
	        'parentController'=>'Configuration',
	        'controller_name' => 'Notification Type show'. $notificationType->getName(),
	        'pageSubTitle' => 'All notifications types',
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, NotificationType $notificationType, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NotificationTypeType::class, $notificationType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_config_notificationType_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/config/notification_type/edit.html.twig', [
            'notification_type' => $notificationType,
            'form' => $form,
	        'parentController'=>'Configuration',
	        'controller_name' => 'Notification Type edit',
	        'pageSubTitle' => 'notifications type edit',
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, NotificationType $notificationType, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$notificationType->getId(), $request->request->get('_token'))) {
            $entityManager->remove($notificationType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dashboard_config_notificationType_index', [], Response::HTTP_SEE_OTHER);
    }
}
