<?php

namespace App\Controller\Dashboard;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

#[Route('/dashboard', name: 'app_dashboard_home_')]
class DashboardHomeController extends AbstractController
{
	
	
	public function __construct(
		private EntityManagerInterface $entityManager,
		private ChartBuilderInterface $chartBuilder
	){
	}
	
	#[Route('/', name: 'index')]
    public function index(): Response
    {
		
		$chart = $this->revenueChart();
        return $this->render('dashboard/dashboard_home/index.html.twig', [
            'controller_name' => 'DashboardHomeController',
	        'parentController'=> 'DashboardController',
	        'chart' => $chart,
	        'apartementsChart' => $this->project(99, 5, 45),
	        'villasChart' => $this->project(57, 21, 35),
	        'duplexChart' => $this->project(44, 1, 8),
        ]);
    }
	
	public function revenueChart(){
		$chart = $this->chartBuilder->createChart(Chart::TYPE_LINE);
		
		$chart->setData([
			'labels' => ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet'],
			'datasets' => [
				[
					'label' => 'Villas',
					'backgroundColor' => 'rgb(255, 99, 132, .4)',
					'borderColor' => 'rgb(255, 99, 132)',
					'data' => [2, 10, 5, 18, 20, 30, 45],
					'tension' => 0.4,
				],
				[
					'label' => 'Apartements️',
					'backgroundColor' => 'rgba(45, 220, 126, .4)',
					'borderColor' => 'rgba(45, 220, 126)',
					'data' => [10, 15, 4, 3, 25, 41, 25],
					'tension' => 0.4,
				],
			],
		]);
		
		$chart->setOptions([
			'maintainAspectRatio' => false,
		]);
	return $chart;
	}
	public function project($v, $r, $d){
		$chart = $this->chartBuilder->createChart(Chart::TYPE_DOUGHNUT);
		
		$chart->setData([
			'labels' => ['Vendus', 'Reservé', 'Disponible'],
			'datasets' => [
				[
					'data' => [$v, $r, $d],
					'backgroundColor' => ['rgb(255, 99, 132)', 'rgba(45, 220, 126)', 'rgba(255, 206, 86)'],
				],
				
			],
		]);
		
		$chart->setOptions([
			'maintainAspectRatio' => false,
		]);
		return $chart;
	}
	
	
}
