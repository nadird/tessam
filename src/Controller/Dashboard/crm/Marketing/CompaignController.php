<?php

namespace App\Controller\Dashboard\crm\Marketing;

use App\Entity\CFormatBien;
use App\Entity\Compaign;
use App\Entity\ContactJob;
use App\Entity\Customer;
use App\Entity\CustomerState;
use App\Entity\Employee;
use App\Entity\InteractionHistory;
use App\Entity\InteractionSource;
use App\Entity\InteractionType;
use App\Entity\Lead;
use App\Entity\LeadSource;
use App\Entity\LeadState;
use App\Entity\TraficTracking;
use App\Entity\Visitor;
use App\Form\Dashboard\crm\marketing\CompaignFormType;
use App\Service\ImageUploadService;
use App\Service\QrCodeService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/dashboard/crm/compaigns', name: 'app_dashboard_crm_compaigns_')]
class CompaignController extends AbstractController
{
	public function __construct(
		private EntityManagerInterface $entityManager,
		private TranslatorInterface $translator,
		private RequestStack $requestStack,
		private LoggerInterface $logger,
		private PaginatorInterface $paginator,
		private ImageUploadService $imageUploadService,
		private QrCodeService $qrCodeService
	)
	{
	}
	
	#[Route('/', name: 'index')]
    public function index(): Response
    {
	    $compaigns = $this->paginator->paginate(
		    $this->entityManager->getRepository(Compaign::class)->findAll(),
		    $this->requestStack->getCurrentRequest()->query->getInt('page',1),
		    10
	    );
		$form = $this->createForm(CompaignFormType::class,$compaign = new Compaign());
		$form->handleRequest($this->requestStack->getCurrentRequest());
		if($form->isSubmitted() && $form->isValid()){
			if($form->get('image')->getData()){
				$compaign->setImage($this->imageUploadService->uploadImage($form->get('image')->getData(),$this->getParameter('public_directory')));
			}
			if($form->get('video')->getData()){
				$compaign->setVideo($this->imageUploadService->uploadImage($form->get('video')->getData(),$this->getParameter('public_directory')));
			}
			$compaign->setDateAdd(new \DateTime());
			$compaign->setEmployee($this->getUser());
			$compaign->setIsActive(true);
			$this->entityManager->persist($compaign);
			$this->entityManager->flush();
			$finalUrl = $compaign->getUrl()."?utm_source=".$compaign->getType()->getCode()."&utm_campaign=".$compaign->getId();
			$compaign->setFinalUrl($finalUrl);
			$compaign->setQr($this->qrCodeService->createQrFromLink($finalUrl,'Compaign_id_'.$compaign->getId()));
			$this->entityManager->persist($compaign);
			$this->entityManager->flush();
			$this->addFlash('success','Campaign Added Successfully');
			return $this->redirectToRoute('app_dashboard_crm_compaigns_index');
		}
		
		
        return $this->render('dashboard/crm/compaign/index.html.twig', [
            'controller_name' => 'Compaigns Index',
	        'parentController'=>'Compaigns',
	        'compaigns'=>$compaigns,
	        'form'=>$form->createView(),
	        
        ]);
    }
	#[Route('/compagign/show/{id}', name: 'compaign')]
	public function show($id): Response
	{
		$compaign = $this->entityManager->getRepository(Compaign::class)->find($id);
		if(!$compaign){
			$this->addFlash('danger','Compaign Not Found');
			return $this->redirectToRoute('app_dashboard_crm_compaigns_index');
		}
		
		return $this->render('dashboard/crm/compaign/compaign.html.twig', [
			'controller_name' => 'Compaign '.$compaign->getName(),
			'parentController'=>'Compaigns',
			'compaign'=>$compaign,
		]);
		
	}
	
	#[Route('/compagign/edit/{id}', name: 'edit')]
	public function edit($id): Response
	{
		$compaign = $this->entityManager->getRepository(Compaign::class)->find($id);
		if(!$compaign){
			$this->addFlash('danger','Compaign Not Found');
			return $this->redirectToRoute('app_dashboard_crm_compaigns_index');
		}
		$form = $this->createForm(CompaignFormType::class,$compaign);
		$form->handleRequest($this->requestStack->getCurrentRequest());
		if($form->isSubmitted() && $form->isValid()){
			if($form->get('image')->getData()){
				$compaign->setImage($this->imageUploadService->uploadImage($form->get('image')->getData(),$this->getParameter('public_directory')));
			}
			if($form->get('video')->getData()){
				$compaign->setVideo($this->imageUploadService->uploadImage($form->get('video')->getData(),$this->getParameter('public_directory')));
			}
			$this->entityManager->persist($compaign);
			$this->entityManager->flush();
			$this->addFlash('success','Compaign Updated Successfully');
			return $this->redirectToRoute('app_dashboard_crm_compaigns_index');
		}
		
		return $this->render('dashboard/crm/compaign/edit.html.twig', [
			'controller_name' => 'Compaign '.$compaign->getName(),
			'parentController'=>'Compaigns',
			'compaign'=>$compaign,
			'form'=>$form->createView(),
			
		]);
		
	}
	
	#[Route('/trackVisitors', name: 'trackvisitors')]
	public function trackVisitors():Response
	{
		$filePath = $this->getParameter('kernel.project_dir').'/FakeData.csv';
		//dd($filePath);
		$handle = fopen($filePath, 'r');
		if (!$handle) {
			throw $this->createNotFoundException('File not found.');
		}
		$data = [];
		while (($row = fgetcsv($handle)) !== false) {
			// $row is an array representing a line in the CSV file
			$data[] = $row;
		}
		fclose($handle);
		$noms=[];
		$prenoms=[];
		$phones=[];
		foreach ($data as $datum) {
			$datum = explode(';', $datum[0]);
			if(isset($datum[0])){
				$noms[]=$datum[0];
			}
			if(isset($datum[1])){
				$prenoms[]=$datum[1];
			}
			if(isset($datum[2])){
				$phones[]=trim($datum[2]);
			}
		}
		$newLeads= [];
		for($i=0;$i<100;$i++) {
			$lead = new Lead();
			$lead->setDateAdd($this->randomDay());
			/*
			 *
			 */
			$lead->setFirstName($noms[array_rand($noms)]);
			$lead->setLastName($prenoms[array_rand($prenoms)]);
			$lead->setPhone1($phones[array_rand($phones)]);
			$lead->setEmail1($lead->getFirstName() . '.' . $lead->getLastName() . '@gmail.com');
			$lead->setIsDeleted(false);
			$lead->setSource($this->entityManager->getRepository(LeadSource::class)->find(rand(1, 5)));
			$lead->setIsValid(rand(0, 1));
			$lead->setJob(
				$this->entityManager->getRepository(ContactJob::class)->find(rand(1, 5))
			);
			$lead->addFormatBienInterest(
				$this->entityManager->getRepository(CFormatBien::class)->find(rand(1, 14))
			);
			$lead->setIsProcessed(false);
			$lead->setIsDeleted(false);
			$lead->setIsTransformedToCustomer(false);
			$lead->setLastInteraction($lead->getDateAdd());
			$lead->setState(
				$this->entityManager->getRepository(LeadState::class)->findOneBy(['code' => 'new'])
			);
			$lead->setAddress('Alger');
			$this->entityManager->persist($lead);
			$newLeads[]=$lead;
			
		}
		//$this->entityManager->flush();
		//dd($newLeads);
		$customers=[];
		foreach ($newLeads as $lead) {
			$chance=rand(0,100);
			if($chance<30){
				//Affected
				$lead->setEmployee($this->entityManager->getRepository(Employee::class)->findOneById(rand(4, 9)));
				$lead->setState(
					$this->entityManager->getRepository(LeadState::class)->findOneBy(['code' => 'AFF'])
				);
				$lastInteraction = $this->addRandomdays($lead->getDateAdd());
				$lead->setLastInteraction($lastInteraction);
				$interactionHistory = new InteractionHistory();
				$interactionHistory->setDateAdd($lastInteraction);
				$interactionHistory->setLead($lead);
				$interactionHistory->setEmployee($lead->getEmployee());
				$interactionHistory->setIsDeleted(false);
				$interactionHistory->setType(
					$this->entityManager->getRepository(InteractionType::class)->findOneByCode('AUT')
				);
				$interactionHistory->setSource(
					$this->entityManager->getRepository(InteractionSource::class)->findOneByCode('SYS')
				);
				$interactionHistory->setText('Affected');
				$interactionHistory->setLeadState($lead->getState());
				$this->entityManager->persist($interactionHistory);
			}
			if($chance>30 && $chance<50){
				$lead->setEmployee($this->entityManager->getRepository(Employee::class)->findOneById(rand(4, 9)));
				$lead->setState(
					$this->entityManager->getRepository(LeadState::class)->findOneBy(['code' => 'AFF'])
				);
				$lastInteraction = $this->addRandomdays($lead->getDateAdd());
				$lead->setLastInteraction($lastInteraction);
				$interactionHistory = new InteractionHistory();
				$interactionHistory->setDateAdd($lastInteraction);
				$interactionHistory->setLead($lead);
				$interactionHistory->setEmployee($lead->getEmployee());
				$interactionHistory->setIsDeleted(false);
				$interactionHistory->setType(
					$this->entityManager->getRepository(InteractionType::class)->findOneByCode('AUT')
				);
				$interactionHistory->setSource(
					$this->entityManager->getRepository(InteractionSource::class)->findOneByCode('SYS')
				);
				$interactionHistory->setText('Affected');
				$interactionHistory->setLeadState($lead->getState());
				$this->entityManager->persist($interactionHistory);
				$lead->setState(
					$this->entityManager->getRepository(LeadState::class)->findOneBy(['code' => 'QAL'])
				);
				$lastInteraction =$this->addRandomdays($lead->getDateAdd());
				$lead->setLastInteraction($lastInteraction);
				$interactionHistory = new InteractionHistory();
				$interactionHistory->setDateAdd($lastInteraction);
				$interactionHistory->setLead($lead);
				$interactionHistory->setEmployee($lead->getEmployee());
				$interactionHistory->setIsDeleted(false);
				$interactionHistory->setType(
					$this->entityManager->getRepository(InteractionType::class)->findOneById(rand(1, 5))
				);
				$interactionHistory->setSource(
					$this->entityManager->getRepository(InteractionSource::class)->findOneById(rand(1, 5))
				);
				$interactionHistory->setText('Qualified');
				$interactionHistory->setLeadState($lead->getState());
				$this->entityManager->persist($interactionHistory);
				
			}
			if($chance>50 && $chance<80){
				//Affected then qualified then Transformed
				
				$lead->setEmployee($this->entityManager->getRepository(Employee::class)->findOneById(rand(4, 9)));
				$lead->setState(
					$this->entityManager->getRepository(LeadState::class)->findOneBy(['code' => 'AFF'])
				);
				$lastInteraction = $this->addRandomdays($lead->getDateAdd());
				$lead->setLastInteraction($lastInteraction);
				$interactionHistory = new InteractionHistory();
				$interactionHistory->setDateAdd($lastInteraction);
				$interactionHistory->setLead($lead);
				$interactionHistory->setEmployee($lead->getEmployee());
				$interactionHistory->setIsDeleted(false);
				$interactionHistory->setType(
					$this->entityManager->getRepository(InteractionType::class)->findOneByCode('AUT')
				);
				$interactionHistory->setSource(
					$this->entityManager->getRepository(InteractionSource::class)->findOneByCode('SYS')
				);
				$interactionHistory->setText('Affected');
				$interactionHistory->setLeadState($lead->getState());
				$this->entityManager->persist($interactionHistory);
				$lead->setState(
					$this->entityManager->getRepository(LeadState::class)->findOneBy(['code' => 'QAL'])
				);
				$lastInteraction = $this->addRandomdays($lead->getLastInteraction());
				$lead->setLastInteraction($lastInteraction);
				$interactionHistory = new InteractionHistory();
				$interactionHistory->setDateAdd($lastInteraction);
				$interactionHistory->setLead($lead);
				$interactionHistory->setEmployee($lead->getEmployee());
				$interactionHistory->setIsDeleted(false);
				$interactionHistory->setType(
					$this->entityManager->getRepository(InteractionType::class)->findOneById(rand(1, 5))
				);
				$interactionHistory->setSource(
					$this->entityManager->getRepository(InteractionSource::class)->findOneById(rand(1, 5))
				);
				$interactionHistory->setText('Qualified');
				$interactionHistory->setLeadState($lead->getState());
				$this->entityManager->persist($interactionHistory);
				$lead->setState(
					$this->entityManager->getRepository(LeadState::class)->findOneBy(['code' => 'GAG'])
				);
				$lastInteraction =  $this->addRandomdays($lead->getLastInteraction());
				$lead->setLastInteraction($lastInteraction);
				$interactionHistory = new InteractionHistory();
				$interactionHistory->setDateAdd($lastInteraction);
				$interactionHistory->setLead($lead);
				$interactionHistory->setEmployee($lead->getEmployee());
				$interactionHistory->setIsDeleted(false);
				$lead->setIsTransformedToCustomer(true);
				$interactionHistory->setType(
					$this->entityManager->getRepository(InteractionType::class)->findOneById(rand(1, 5))
				);
				$interactionHistory->setSource(
					$this->entityManager->getRepository(InteractionSource::class)->findOneById(rand(1, 5))
				);
				$interactionHistory->setText('Gagné');
				$interactionHistory->setLeadState(
					$this->entityManager->getRepository(LeadState::class)->findOneBy(['code' => 'GAG'])
				);
				$this->entityManager->persist($interactionHistory);
				$customer = new Customer();
				$customer->setDateAdd($lastInteraction);
				$customer->setFirstName($lead->getFirstName());
				$customer->setLastName($lead->getLastName());
				$customer->setPhone($lead->getPhone1());
				$customer->setEmail($lead->getEmail1());
				$customer->setIsDeleted(false);
				$customer->setEmployee($lead->getEmployee());
				$customer->setLead($lead);
				$customer->setIsDeleted(false);
				$customer->setState(
					$this->entityManager->getRepository(CustomerState::class)->findOneBy(['code' => 'CRS'])
				);
				$customer->setAddress($lead->getAddress());
				$this->entityManager->persist($customer);
				$customers[]=$customer;
				
				
			}if($chance>80 && $chance<100){
			
			}
			
			//$this->entityManager->flush();
			dd($newLeads,$customers);
		}
		
		return $this->render('dashboard/crm/compaign/trackvisitors.html.twig', [
			'controller_name' => 'Compaigns Track Visitors',
			'parentController'=>'Compaigns',
			
		]);
	}
	public function addRandomdays($date){
		$daysToAdd = rand(0, 4);
		$date->modify("+{$daysToAdd} days");
		return $date;
	}
	public function randomDay(){
		$daysAgo = rand(0, 100);
		$today = new \DateTime();
		return $today->modify("-{$daysAgo} days");
	}
}
