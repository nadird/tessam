<?php
	// src/Command/CreateUserCommand.php
	namespace App\Command;
	

	use App\Entity\Employee;
	use Symfony\Component\Console\Attribute\AsCommand;
	use Symfony\Component\Console\Command\Command;
	use Symfony\Component\Console\Input\InputInterface;
	use Symfony\Component\Console\Output\OutputInterface;
	use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
	use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
	use Symfony\Component\Uid\Uuid;

// the name of the command is what users type after "php bin/console"
	#[AsCommand(name: 'app:launchProccess', description: 'Arrange Areas ',)]
	class LaunchProcessCommand extends Command
	{
		public function __construct(
			private \Doctrine\ORM\EntityManagerInterface $entityManager,
			ParameterBagInterface $params,
			private UserPasswordHasherInterface $userPasswordHasher
		
		){
			$this->params = $params;
			parent::__construct();
		}
		
		protected function execute(InputInterface $input, OutputInterface $output): int
		{
			$output->writeln([
				'Start',
				'============',
				'',
			]);
			$employee = new Employee();
			$employee->setEmail('cnend123@gmail.com');
			$employee->setPassword($this->userPasswordHasher->hashPassword($employee, '0670002516'));
			$employee->setRoles(['ROLE_SUPER_ADMIN']);
			$employee->setJob(
				$this->entityManager->getRepository(\App\Entity\EmplJobs::class)->findOneBy(['name' => 'Super Admin'])
			);
			$employee->setFirstName('Nadir');
			$employee->setLastName('Delimi');
			$employee->setIsActive(true);
			$employee->setDob(new \DateTime('1980-01-01'));
			$employee->setPicture('default.png');
			$this->entityManager->persist($employee);
			$this->entityManager->flush();
			
			return Command::SUCCESS;
			
		}
		
		
		
		
		
		
		
		
		
		
		
		
	}