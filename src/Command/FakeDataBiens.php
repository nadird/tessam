<?php
	// src/Command/CreateUserCommand.php
	namespace App\Command;
	
	use App\Entity\CbienState;
	use App\Entity\CFormatBien;
	use App\Entity\ContactJob;
	use App\Entity\CProject;
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
	#[AsCommand(name: 'app:fake_biens', description: 'Creates fake Orders ',)]
	class FakeDataBiens extends Command
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
			$projects = $this->entityManager->getRepository(CProject::class)->findAll();
			foreach ($projects as $project){
				$formatBiens = $this->entityManager->getRepository(CFormatBien::class)->findBy(['project'=>$project]);
				$n=0;
				foreach ($formatBiens as $formatBien){
					if($formatBien->getType()->isIsHabitation()){
						if($formatBien->getType()->isIsCollectif()){
							$building= new \App\Entity\CBuiliding();
							$building->setName('Bt1');
							$building->setOrdre(1);
							$building->setProject($project);
							$this->entityManager->persist($building);
							$etage = 1;
							for($i=0;$i<10;$i++){
								if($i%2==0){
									$etage++;
								}
								$n++;
								$this->createBien($formatBien,$project,$building,$etage,$n);
							}
							$output->writeln('Created '.$n.' biens');
							$building= new \App\Entity\CBuiliding();
							$building->setName('Bt2');
							$building->setOrdre(2);
							$building->setProject($project);
							$this->entityManager->persist($building);
							$etage = 1;
							for($i=0;$i<10;$i++){
								if($i%2==0){
									$etage++;
								}
								$n++;
								$this->createBien($formatBien,$project,$building,$etage,$n);
							}
							$output->writeln('Created '.$n.' biens');
							$building= new \App\Entity\CBuiliding();
							$building->setName('Bt2');
							$building->setOrdre(2);
							$building->setProject($project);
							$this->entityManager->persist($building);
							$etage = 1;
							for($i=0;$i<10;$i++){
								if($i%2==0){
									$etage++;
								}
								$n++;
								$this->createBien($formatBien,$project,$building,$etage,$n);
							}
							
							$output->writeln('Created '.$n.' biens');
						}
						$output->writeln('Created biens for project '.$project->getName());
					
					}
					$this->entityManager->flush();
				}
				
			}
			
			
			
			return Command::SUCCESS;
			
		}
		public function addRandomdays($date): \DateTime
		{
			
			
			$interval = new \DateInterval('P1W');
			$date->add($interval);
			return $date;
		}
		
		public function randomDay(): \DateTime
		{
			$faker = Factory::create('fr_FR');
			$date = $faker->dateTimeBetween('-3 months', '+3 days');
			return $date;
		}
		public function createBien($formatBien,$project,$building,$etage,$n)
		{
			$bien = new \App\Entity\Cbien();
			$bien->setName($formatBien->getName().' N°'.$n);
			$bien->setProject($project);
			$bien->setNumerotation($n);
			$bien->setBuilding($building);
			$bien->setFloor($etage);
			$bien->setFormat($formatBien);
			$bien->setType($formatBien->getType());
			$bien->setPriceTtc($formatBien->getPrice());
			$bien->setFinalPrice($formatBien->getPrice()*1.2);
			$bien->setIsHold(false);
			$bien->setIsSold(false);
			$bien->setState(
				$this->entityManager->getRepository(CbienState::class)->findOneBy(['code'=>'DIS'])
			);
			$bien->setDateAdd($this->randomDay());
			$bien->setExactArea($formatBien->getArea());
			$bien->setUtileArea($formatBien->getArea()*0.8);
			$this->entityManager->persist($bien);
		}
		
		
	}