<?php
	// src/Command/CreateUserCommand.php
	namespace App\Command;
	
	use App\Entity\TraficTracking;
	use App\Entity\Visitor;
	use Symfony\Component\Console\Attribute\AsCommand;
	use Symfony\Component\Console\Command\Command;
	use Symfony\Component\Console\Input\InputInterface;
	use Symfony\Component\Console\Output\OutputInterface;

// the name of the command is what users type after "php bin/console"
	#[AsCommand(name: 'app:trackin-to-visitor', description: 'Transform tracking records to visitor',)]
	class TrackinToVisitorCommand extends Command
	{
		public function __construct(private \Doctrine\ORM\EntityManagerInterface $entityManager)
		{
			parent::__construct();
		}
		
		protected function execute(InputInterface $input, OutputInterface $output): int
		{
			$commandStats = [
				'command' => 'app:trackin-to-visitor',
				'countDeleted' => 0,
				'countNewVisiros' => 0,
				'countNewReturningVisitor' => 0,
				'errors' => 0,
			];
			
			$tracking = $this->entityManager->getRepository(TraficTracking::class)->findNonDeleted();
			$grouppedTracks = [];
			foreach ($tracking as $track) {
				if ($track->getAcceptLanguage() == null) {
					$track->setIsDeleted(true);
					$commandStats['countDeleted']++;
				} else {
					$grouppedTracks[$track->getEvssid()][] = $track;
				}
			}
			
			foreach ($grouppedTracks as $key => $grouppedTrack) {
				$visitor = $this->entityManager->getRepository(Visitor::class)->findOneBy(['evssid' => $key]) ?? new Visitor();
				
				if (!$visitor->getId()) {
					$visitor->setEvssid($key);
					$visitor->setDateAdd(
						$grouppedTrack[0]->getDateAdd()
					);
					$visitor->setIsDeleted(false);
					$this->entityManager->persist($visitor);
					$commandStats['countNewVisiros']++;
				}else{
					$commandStats['countNewReturningVisitor']++;
				}
				
				foreach ($grouppedTrack as $track) {
					if($track->getUtmSource()){
						$visitor->setUtmSource($track->getUtmSource());
					}
					if($track->getUtmCampaign()){
						$visitor->setCompaign($track->getUtmCampaign());}
					$track->setVisitor($visitor);
					$track->setIsDeleted(true);
					$this->entityManager->persist($track);
				}
			}
			
			// Perform a single flush after processing all records
			$this->entityManager->flush();
			$output->writeln('Command stats:');
			$output->writeln(json_encode($commandStats));
			// this method must return an integer number with the "exit status code"
			return Command::SUCCESS;
			
			// or return this if some error happened during the execution
			// (it's equivalent to returning int(1))
			// return Command::FAILURE;
			
			// or return this to indicate incorrect command usage; e.g. invalid options
			// or missing arguments (it's equivalent to returning int(2))
			// return Command::INVALID
		}
	}