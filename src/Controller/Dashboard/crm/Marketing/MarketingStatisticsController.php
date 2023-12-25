<?php
	namespace App\Controller\Dashboard\crm\Marketing;
	

	use App\Entity\Customer;
	use App\Entity\Lead;
	use App\Entity\Order;
	use App\Entity\Visitor;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\RequestStack;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;
	use Symfony\UX\Chartjs\Model\Chart;
	
	#[Route('/dashboard/crm/marketing/statistics', name: 'app_dashboard_crm_marketing_statistics_')]
	class MarketingStatisticsController extends AbstractController
	{
		public function __construct(
			private EntityManagerInterface       $entityManager,
			private RequestStack                 $requestStack,
		)
		{
		}
		
		#[Route('/', name: 'index')]
		public function index(): Response
		{
			$leadsStats = $this->leadsStats();
		
	
			
			return $this->render('dashboard/crm/marketing/statistics/index.html.twig', [
				'parentController' => 'Marketing',
				'controller_name' => 'Marketing Index',
				'leadsStats' => $this->leadsStats(),
				'customersStats' => $this->ordersStats(),
				'ordersStats' => $this->ordersStats(),
				'leadsSatesChart' => $this->leadStatesChart($leadsStats),
				'leadsSourcesChart' => $this->leadSource($leadsStats),
				'leadsJobsChart' => $this->LeadJob($leadsStats),
				'leadInterestChart' => $this->LeadInterestChart($leadsStats),
				'lead30LastDaysChart' => $this->Lead30LastDaysChart($this->leads30LasDays()),
				'leadCompaignChart' => $this->LeadCompaignChart($leadsStats),
				'visitors30LastDaysChart' => $this->Visitors30LastDaysChart($this->visitors30LasDays()),
				
			]);
		}
		public function leads30LasDays():array
		{
			$leads = $this->entityManager->getRepository(Lead::class)->leadsLast30DaysNotdeleted();
			$dateCounts = [];
			for ($i = 29; $i >= 0; $i--) {
				$date = new \DateTime();
				$date->modify("-$i days");
				$dateCounts[$date->format('Y-m-d')] = 0;
			}
			
			// Iterate over the leads and count them per day
			foreach ($leads as $lead) {
				$dateAdded = $lead->getDateAdd();
				if ($dateAdded) {
					$dateKey = $dateAdded->format('Y-m-d');
					if (isset($dateCounts[$dateKey])) {
						$dateCounts[$dateKey]++;
					}
				}
			}
			return $dateCounts;
			
		}
		public function visitors30LasDays():array
		{
			$visitors = $this->entityManager->getRepository(Visitor::class)->findVisitors30LastDays();
			$dateCounts = [];
			for ($i = 29; $i >= 0; $i--) {
				$date = new \DateTime();
				$date->modify("-$i days");
				$dateCounts[$date->format('Y-m-d')] = 0;
			}
			
			// Iterate over the leads and count them per day
			foreach ($visitors as $visitor) {
				$dateAdded = $visitor->getDateAdd();
				if ($dateAdded) {
					$dateKey = $dateAdded->format('Y-m-d');
					if (isset($dateCounts[$dateKey])) {
						$dateCounts[$dateKey]++;
					}
				}
			}
			return $dateCounts;
			
		}
		public function leadsStats(){
			$leads = $this->entityManager->getRepository(Lead::class)->findAll();
			$leadsStats = [];
			$leadsStats['total'] = 0;
			foreach ($leads as $lead){
				$leadsStats['total']+=1;
				if(!isset($leadsStats['states'][$lead->getState()->getName()])){
					$leadsStats['states'][$lead->getState()->getName()] = [
						'state'=>$lead->getState(),
						'total'=>1];
				}else{
					$leadsStats['states'][$lead->getState()->getName()]['total'] += 1;
				}
				if ($lead->getSource() === null) {
					// Option 1: Skip this lead
					continue;
					
				}elseif (!isset($leadsStats['sources'][$lead->getSource()->getName()])){
					$leadsStats['sources'][$lead->getSource()->getName()] = [
						'source'=>$lead->getSource(),
						'total'=>1
					];
				}else{
					$leadsStats['sources'][$lead->getSource()->getName()]['total'] += 1;
				}
				$leadsInterest = $lead->getFormatBienInterest();
				
				foreach ($leadsInterest as $leadInterest){
					if(!isset($leadsStats['interests'][$leadInterest->getName()])){
						$leadsStats['interests'][$leadInterest->getName()] = [
							'interest'=>$leadInterest,
							'total'=>1];
					}else {
						$leadsStats['interests'][$leadInterest->getName()]['total'] += 1;
					}
				}
				if(!isset($leadsStats['jobs'][$lead->getJob()->getName()])){
					$leadsStats['jobs'][$lead->getJob()->getName()] = [
						'job'=>$lead->getJob(),
						'total'=>1
					];
				}else{
					$leadsStats['jobs'][$lead->getJob()->getName()]['total'] += 1;
				}
				if(!isset($leadsStats['toCustomer'][$lead->isIsTransformedToCustomer()? 'Yes':'No'])){
					$leadsStats['toCustomer'][$lead->isIsTransformedToCustomer()? 'Yes':'No'] = 0;
				}else{
					$leadsStats['toCustomer'][$lead->isIsTransformedToCustomer()? 'Yes':'No'] += 1;
				}
				if($lead->getUtmCompaign()){
				if( !isset($leadsStats['compaigns'][$lead->getUtmCompaign()->getName()])){
					$leadsStats['compaigns'][$lead->getUtmCompaign()->getName()] = [
						'compaign'=>$lead->getUtmCompaign(),
						'total'=>1];
				}else{
					
					$leadsStats['compaigns'][$lead->getUtmCompaign()->getName()]['total'] += 1;
				}
				}
			}
			return $leadsStats;
		}
		public function customersStats():array
		{
			$customers = $this->entityManager->getRepository(Customer::class)->findAll();
			$customersStats = [];
			$customersStats['total'] = 0;
			foreach ($customers as $customer){
				$customersStats['total']+=1;
				if(!isset($customersStats['states'][$customer->getState()->getName()])){
					$customersStats['states'][$customer->getState()->getName()] = 0;
				}else{
					$customersStats['states'][$customer->getState()->getName()] += 1;
				}
			}
			return $customersStats;
		}
		public function ordersStats():array{
			$orders = $this->entityManager->getRepository(Order::class)->findAll();
			$ordersStats = [];
			$ordersStats['total'] = 0;
			foreach ($orders as $order){
				$ordersStats['total']+=1;
				if(!isset($ordersStats['states'][$order->getOrderState()->getName()])){
					$ordersStats['states'][$order->getOrderState()->getName()] = 0;
				}else{
					$ordersStats['states'][$order->getOrderState()->getName()] += 1;
				}
				if(!isset($ordersStats['paymentMethod'][$order->getOrderType()->getName()])){
					$ordersStats['paymentMethod'][$order->getOrderType()->getName()] = 0;
					}else {
					$ordersStats['paymentMethod'][$order->getOrderType()->getName()] += 1;
				}
				if(!isset($ordersStats['paiementDue'])) {
					$ordersStats['paiementDue'] = 0;
				}else{
					$ordersStats['paiementDue'] += $order->getTotalHt();
				}
				$totalpaied = 0;
				foreach ($order->getPaiementHistories() as $paiement){
					$totalpaied += $paiement->getTotal();
				}
				if(!isset($ordersStats['paiementPaied'])) {
					$ordersStats['paiementPaied'] = 0;
				}else{
					$ordersStats['paiementPaied'] += $totalpaied;
				}
			}
			return $ordersStats;
		}
		
		public function leadStatesChart($leadsStats): Chart
		{
			$chart = new Chart('doughnut');
			
			$leadsStats = $this->leadsStats($leadsStats);
			$labels = [];
			$data = [];
			$backgroundColor = [];
			
			foreach ($leadsStats['states'] as $state) {
				if (!$state['state']) {
					continue; // Skip if state object is null
				}
				
				// Collect labels, data, and background colors
				$labels[] = $state['state']->getName();
				$data[] = $state['total'];
				$backgroundColor[] = $state['state']->getColor();
			}
			
			// Single dataset for all states
			$datasets = [
				[
					'label' => 'Lead States',
					'backgroundColor' => $backgroundColor,
					'data' => $data,
				]
			];
			
			$chart->setData([
				'labels' => $labels,
				'datasets' => $datasets,
			]);
			
			return $chart;
		}
		public function leadSource($leadsStats): Chart
		{
			$chart = new Chart('doughnut');
			
			$leadsStats = $this->leadsStats($leadsStats);
			$labels = [];
			$data = [];
			$backgroundColor = [];
			
			foreach ($leadsStats['sources'] as $state) {
				if (!$state['source']) {
					continue; // Skip if state object is null
				}
				
				// Collect labels, data, and background colors
				$labels[] = $state['source']->getName();
				$data[] = $state['total'];
				$backgroundColor[] = $state['source']->getColor();
			}
			
			// Single dataset for all states
			$datasets = [
				[
					'label' => 'Sources',
					'backgroundColor' => $backgroundColor,
					'data' => $data,
				]
			];
			
			$chart->setData([
				'labels' => $labels,
				'datasets' => $datasets,
			]);
			
			
			return $chart;
		}
		public function LeadJob($leadsStats): Chart
		{
			$chart = new Chart('doughnut');
			
			$leadsStats = $this->leadsStats($leadsStats);
			$labels = [];
			$data = [];
			$backgroundColor = [];
			
			foreach ($leadsStats['jobs'] as $state) {
				if (!$state['job']) {
					continue; // Skip if state object is null
				}
				
				// Collect labels, data, and background colors
				$labels[] = $state['job']->getName();
				$data[] = $state['total'];
				$backgroundColor[] = $state['job']->getColor();
			}
			
			// Single dataset for all states
			$datasets = [
				[
					'label' => 'Jobs',
					'backgroundColor' => $backgroundColor,
					'data' => $data,
				]
			];
			
			$chart->setData([
				'labels' => $labels,
				'datasets' => $datasets,
			]);
			
			return $chart;
		}
		public function LeadInterestChart($leadsStats): Chart
		{
			$chart = new Chart('doughnut');
			
			$leadsStats = $this->leadsStats($leadsStats);
			$labels = [];
			$data = [];
			$backgroundColor = [];
			$i = 0;
			$colors = ['red','blue','green','yellow','orange','purple','pink','brown','grey','black','cyan','magenta','lime','teal','indigo','maroon','navy','olive','fuchsia','aqua','silver','white'];
			foreach ($leadsStats['interests'] as $state) {
				if (!$state['interest']) {
					continue; // Skip if state object is null
				}
				
				// Collect labels, data, and background colors
				$labels[] = $state['interest']->getName();
				$data[] = $state['total'];
				$backgroundColor[] = $colors[$i];
				$i++;
			}
			
			// Single dataset for all states
			$datasets = [
				[
					'label' => 'Jobs',
					'backgroundColor' => $backgroundColor,
					'data' => $data,
				]
			];
			
			$chart->setData([
				'labels' => $labels,
				'datasets' => $datasets,
			]);
			
			return $chart;
		}
		public function Lead30LastDaysChart($leadsCount): Chart {
			$chart = new Chart('line');
			
			// Extracting labels (dates) and data (lead counts)
			$labels = array_keys($leadsCount);
			$data = array_values($leadsCount);
			
			// Configure the chart
			$chart->setData([
				'labels' => $labels,
				'datasets' => [
					[
						'label' => 'New Leads per Day',
						'backgroundColor' => 'rgba(0, 123, 255, 0.5)',
						'borderColor' => 'rgba(0, 123, 255, 1)',
						'data' => $data,
					],
				],
			]);
			$chart->setOptions([
				'scales' => [
					'yAxes' => [
						[
							'ticks' => [
								'beginAtZero' => true,
							],
						],
					],
					'borderJoinStyle'=> 'bevel',
					'tension'=>0.4,
				],
			]);
			
			// Optional: Configure additional chart options here, if needed
			
			return $chart;
		}
		
		public function LeadCompaignChart($leadsStats): Chart
		{
			$chart = new Chart('bar');
			
			$leadsStats = $this->leadsStats();
			$labels = [];
			$data = [];
			$backgroundColor = [];
			//dd($leadsStats);
			if(!isset($leadsStats['compaigns'])){
				return $chart;
			}
			foreach ($leadsStats['compaigns'] as $state) {
				if (!$state['compaign']) {
					continue; // Skip if state object is null
				}
				
				// Collect labels, data, and background colors
				$labels[] = $state['compaign']->getName();
				$data[] = $state['total'];
				$backgroundColor[] = $state['compaign']->getType()->getColor();
			}
			
			$datasets = [
				[
					'label' => 'Leads',
					'backgroundColor' => $backgroundColor,
					'data' => $data,
				]
			];
			$chart->setData([
				'labels' => $labels,
				'datasets' => $datasets,
			]);
			
			return $chart;
		}
		
		public function Visitors30LastDaysChart($leadsCount): Chart {
			$chart = new Chart('line');
			
			// Extracting labels (dates) and data (lead counts)
			$labels = array_keys($leadsCount);
			$data = array_values($leadsCount);
			
			// Configure the chart
			$chart->setData([
				'labels' => $labels,
				'datasets' => [
					[
						'label' => 'New Leads per Day',
						'backgroundColor' => 'rgba(0, 123, 255, 0.5)',
						'borderColor' => 'rgba(0, 123, 255, 1)',
						'data' => $data,
					],
				],
			]);
			$chart->setOptions([
				'scales' => [
					'yAxes' => [
						[
							'ticks' => [
								'beginAtZero' => true,
							],
						],
					],
					'borderJoinStyle'=> 'bevel',
					'tension'=>0.4,
				],
			]);
			
			// Optional: Configure additional chart options here, if needed
			
			return $chart;
		}
		
		
		
		
	}