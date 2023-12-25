<?php

namespace App\Controller\Dashboard\config;

use App\Entity\TheCompany;
use App\Form\Dashboard\config\TheCompanyType;
use App\Repository\TheCompanyRepository;
use App\Service\ImageUploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/dashboard/config/paiementType/thecompany/config',name: 'app_dashboard_config_thecompany_')]
class TheCompanyConfigController extends AbstractController
{
	public function __construct(
		private EntityManagerInterface $entityManager,
		private RequestStack $request,
		private ImageUploadService $imageUploadService
	)
	{
	}
	#[IsGranted('ROLE_ADMIN')]
	#[Route('/', name: 'index', methods: ['GET', 'POST'])]
    public function index(TheCompanyRepository $theCompanyRepository): Response
    {
		$isTheCompany = $theCompanyRepository->findAll();
		if(!$isTheCompany){
			$theCompany = new TheCompany();
			$theCompany->setName('Affinia');
			$theCompany->setShortDescription('Affinia est une agence immobilière qui vous accompagne dans vos projets immobiliers.');
			$theCompany->setLongDescription('Affinia est une agence immobilière qui vous accompagne dans vos projets immobiliers.');
			$theCompany->setLogoMainLight('logo-main-light.png');
			$theCompany->setLogoMainDark('logo-main-dark.png');
			$theCompany->setLogoMobileLight('logo-mobile-light.png');
			$theCompany->setLogoMobileDark('logo-mobile-dark.png');
			$theCompany->setFavicon('favicon.png');
			$theCompany->setOgImage('og-image.png');
			$theCompany->setImageHome('image-home.png');
			$theCompany->setVideoHome('video-home.mp4');
			$theCompany->setSecondText('Affinia est une agence immobilière qui vous accompagne dans vos projets immobiliers.');
			$theCompany->setSecondImage('second-image.png');
			$theCompany->setFacebookLink('https://www.facebook.com/');
			$theCompany->setInstagram('https://www.instagram.com/');
			$theCompany->setTwitter('https://twitter.com/');
			$theCompany->setTiktok('https://www.tiktok.com/');
			$theCompany->setLinkedin('https://www.linkedin.com/');
			$theCompany->setMessenger('https://www.messenger.com/');
			$theCompany->setWhatsapp('https://www.whatsapp.com/');
			$theCompany->setViber('https://www.viber.com/');
			$theCompany->setEmail1('');
			$theCompany->setEmail2('');
			$theCompany->setPhone1('');
			$theCompany->setPhone2('');
			$theCompany->setRaisonSocial('');
			$theCompany->setAddress('');
			$theCompany->setLocalisation('');
			$theCompany->setDiplayPrices(false);
			$theCompany->setLogoPrint('logo-print.png');
			$this->entityManager->persist($theCompany);
			$this->entityManager->flush();
		}else{
			$theCompany = $theCompanyRepository->findAll()[0];
		}
	    
	    $form = $this->createForm(TheCompanyType::class, $theCompany);
	    $form->handleRequest($this->request->getMainRequest());
	    
	    if ($form->isSubmitted() && $form->isValid()) {
			if($form->get('logoMainLight')->getData()){
				$theCompany->setLogoMainLight(
					$this->imageUploadService->uploadImage(
						$form->get('logoMainLight')->getData(),
						$this->getParameter('the_company_directory')
					)
				);
			}
			if($form->get('logoMainDark')->getData()){
				$theCompany->setLogoMainDark(
					$this->imageUploadService->uploadImage(
						$form->get('logoMainDark')->getData(),
						$this->getParameter('the_company_directory')
					)
				);
			}
			if($form->get('logoPrint')->getData()){
				$theCompany->setLogoPrint(
					$this->imageUploadService->uploadImage(
						$form->get('logoPrint')->getData(),
						$this->getParameter('the_company_directory')
					)
				);
			}
			
			if($form->get('logoMobileLight')->getData()){
				$theCompany->setLogoMobileLight(
					$this->imageUploadService->uploadImage(
						$form->get('logoMobileLight')->getData(),
						$this->getParameter('the_company_directory')
					)
				);
			}
			if($form->get('logoMobileDark')->getData()){
				$theCompany->setLogoMobileDark(
					$this->imageUploadService->uploadImage(
						$form->get('logoMobileDark')->getData(),
						$this->getParameter('the_company_directory')
					)
				);
			}
			if($form->get('favicon')->getData()){
				$theCompany->setFavicon(
					$this->imageUploadService->uploadImage(
						$form->get('favicon')->getData(),
						$this->getParameter('the_company_directory')
					)
				);
			}
			if($form->get('ogImage')->getData()){
				$theCompany->setOgImage(
					$this->imageUploadService->uploadImage(
						$form->get('ogImage')->getData(),
						$this->getParameter('the_company_directory')
					)
				);
			}
			if($form->get('imageHome')->getData()){
				$theCompany->setImageHome(
					$this->imageUploadService->uploadImage(
						$form->get('imageHome')->getData(),
						$this->getParameter('the_company_directory')
					)
				);
			}
			if($form->get('videoHome')->getData()){
				$theCompany->setVideoHome(
					$this->imageUploadService->uploadImage(
						$form->get('videoHome')->getData(),
						$this->getParameter('the_company_directory')
					)
				);
			}
			if($form->get('secondImage')->getData()){
				$theCompany->setSecondImage(
					$this->imageUploadService->uploadImage(
						$form->get('secondImage')->getData(),
						$this->getParameter('the_company_directory')
					)
				);
			}
			
						
						
		    $this->entityManager->persist($theCompany);
		    $this->entityManager->flush();
		    
		    return $this->redirectToRoute('app_dashboard_config_thecompany_index', [], Response::HTTP_SEE_OTHER);
	    }
        return $this->render('dashboard/config/the_company/index.html.twig', [
            'the_companies' => $theCompanyRepository->findAll(),
	        'the_company' => $theCompany,
	        'form' => $form,
	        'parentController'=>'Configuration',
	        'controller_name' => 'The Company Index',
	        'pageSubTitle' => 'List of all The Companies',
        ]);
    }
}
