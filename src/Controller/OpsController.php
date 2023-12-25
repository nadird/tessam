<?php

namespace App\Controller;

use App\Service\EmailService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OpsController extends AbstractController
{
    #[Route('/ops', name: 'app_ops')]
    public function index(EmailService $emailService): Response
    {
		$emailService->sendmail();
       dd('sent waiting for response');
	
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'OpsController',
        ]);
    }
}
