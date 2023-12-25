<?php
	// src/Command/CreateUserCommand.php
	namespace App\Command;
	
	use App\Entity\CFormatBien;
	use App\Entity\Commune;
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
	use App\Entity\Wilaya;
	use Faker\Factory;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\Console\Attribute\AsCommand;
	use Symfony\Component\Console\Command\Command;
	use Symfony\Component\Console\Input\InputInterface;
	use Symfony\Component\Console\Output\OutputInterface;
	use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
	use function PHPUnit\Framework\isInstanceOf;
	use function PHPUnit\Framework\isType;

// the name of the command is what users type after "php bin/console"
	#[AsCommand(name: 'app:addWilayas', description: 'Creates fakeleads ',)]
	class AddWilayasAndCommunesCommand extends Command
	{
		public function __construct(
			private \Doctrine\ORM\EntityManagerInterface $entityManager,
			ParameterBagInterface $params,
		
		){
			$this->params = $params;
			parent::__construct();
		}
		
		protected function execute(InputInterface $input, OutputInterface $output): int
		{
			$output->writeln([
				'Fake Leads',
				'============',
				'',
			]);
			$projectDir = $this->params->get('kernel.project_dir');
			//$filePath = $this->getParameter('kernel.project_dir') . '/FakeData.csv';
			$filePath = $projectDir . '/datainstall/Wilayas_Algerie.csv';
			//dd($filePath);
			$handle = fopen($filePath, 'r');
			$output->writeln([
				'Opened file',
				'============',
				'',
			]);
			if (!$handle) {
				//throw $this->createNotFoundException('File not found.');
			}
			$data = [];
			while (($row = fgetcsv($handle)) !== false) {
				$data[] = $row;
			}
			fclose($handle);
			//dd($data);
			foreach ($data as $datum) {
				$datum = explode(';', $datum[0]);
				if($this->entityManager->getRepository(Wilaya::class)->findOneByIsoCode($datum[1])){
					$output->writeln([
						'Wilaya already exists',
						'============',
						'',
					]);
					continue;
				}else{
					$wilaya = new Wilaya();
					$wilaya->setName(utf8_encode($datum[0]));
					$wilaya->setIsoCode(intval($datum[1]));
					$this->entityManager->persist($wilaya);
				}
				
			}
			$this->entityManager->flush();
			$output->writeln([
				'Wilayas added',
				'============',
				'',
			]);
			
			$filePath = $projectDir . '/datainstall/Communes_algerie.csv';
			$handle = fopen($filePath, 'r');
			$output->writeln([
				'Opened file Communes',
				'============',
				'',
			]);
			if (!$handle) {
				//throw $this->createNotFoundException('File not found.');
			}
			$data = [];
			while (($row = fgetcsv($handle)) !== false) {
				$data[] = $row;
			}
			fclose($handle);
			
			foreach ($data as $datum) {
				$datum = explode(';', $datum[0]);
					$wilaya = $this->entityManager->getRepository(Wilaya::class)->findOneByIsoCode($datum[2]);
					if($this->entityManager->getRepository(Commune::class)->findOneBy(['name'=>utf8_encode($datum[0])])){
						$output->writeln([
							'Commune already exists',
							'============',
							'',
						]);
						continue;
					}else{
						$commune = new Commune();
						$commune->setName(utf8_encode($datum[0]));
						$commune->setWilaya($wilaya);
						$this->entityManager->persist($commune);
					}
				
				
			}
			$this->entityManager->flush();
			
			
			$this->entityManager->flush();
			return Command::SUCCESS;
			
		}
		
		
		
		
		
		
	
		
		
		
	
		
	}