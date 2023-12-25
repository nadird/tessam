<?php
	// src/Command/CreateUserCommand.php
	namespace App\Command;
	
	use App\Entity\Cbien;
	use App\Entity\CbienState;
	use App\Entity\CFormatBien;
	use App\Entity\ChargeType;
	use App\Entity\Commune;
	use App\Entity\CompaignType;
	use App\Entity\ContactJob;
	use App\Entity\CProjectState;
	use App\Entity\Customer;
	use App\Entity\CustomerState;
	use App\Entity\DocumentType;
	use App\Entity\EmplJobs;
	use App\Entity\Employee;
	use App\Entity\EmplRole;
	use App\Entity\InteractionHistory;
	use App\Entity\InteractionSource;
	use App\Entity\InteractionType;
	use App\Entity\Lead;
	use App\Entity\LeadSource;
	use App\Entity\LeadState;
	use App\Entity\MouvementCompteType;
	use App\Entity\NotificationType;
	use App\Entity\PaiementType;
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
	#[AsCommand(name: 'app:createStatesAndTypes', description: 'Arrange Areas ',)]
	class CreateStatesAndTypes extends Command
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
			$this->createLeadStates();
			$this->createLeadSources();
			$this->createInteractionTypes();
			$this->createInteractionSources();
			$this->createCustomerStates();
			$this->createContactJobs();
			$this->createCbienStates();
			$this->createChargeTypes();
			$this->createCompaignTypes();
			$this->createCprojectState();
			$this->documentTypes();
			$this->createEmployeeJobs();
			$this->createEmployeeRoles();
			$this->createMouvemntTypes();
			$this->createPaymentTypes();
			$this->createNotificationTypes();
			$this->createOrderStates();
			$this->createOrderTypes();
			$this->createProviderOrderStates();
			$this->createProviderTypes();
			$this->createWebContactState();
			$this->createWebContactState();
			$this->createBienAdditionnalState();
			$this->createBienAdditionnalTypes();
			$this->createBankCreditState();
			
			
			return Command::SUCCESS;
			
		}
		private function createLeadStates(){
			
			if ($this->entityManager->getRepository(LeadState::class)->findAll()){
				
				return;
			}
			$state = new LeadState();
			$state->setName('Nouveau');
			$state->setCode('NEW');
			$state->setColor('#1160b7');
			$state->setColorBorder('#1160b7');
			$state->setIsWon(false);
			$state->setIsLost(false);
			$state->setColorText('#FFFFFF');
			$this->entityManager->persist($state);
			$state = new LeadState();
			$state->setName('Affecté');
			$state->setCode('AFF');
			$state->setColor('#D24726');
			$state->setColorBorder('#D24726');
			$state->setIsWon(false);
			$state->setIsLost(false);
			$state->setColorText('#FFFFFF');
			$this->entityManager->persist($state);
			$state = new LeadState();
			$state->setName('Qualifié');
			$state->setCode('QUAL');
			$state->setColor('#FFA500');
			$state->setColorBorder('#FFA500');
			$state->setIsWon(false);
			$state->setIsLost(false);
			$state->setColorText('#FFFFFF');
			$this->entityManager->persist($state);
			$state = new LeadState();
			$state->setName('Perdu');
			$state->setCode('LOST');
			$state->setColor('#FF0000');
			$state->setColorBorder('#FF0000');
			$state->setIsWon(false);
			$state->setIsLost(true);
			$state->setColorText('#FFFFFF');
			$this->entityManager->persist($state);
			$state = new LeadState();
			$state->setName('Gagné');
			$state->setCode('WON');
			$state->setColor('#4caf50');
			$state->setColorBorder('#4caf50');
			$state->setIsWon(true);
			$state->setIsLost(false);
			$state->setColorText('#FFFFFF');
			$this->entityManager->persist($state);
			$state = new LeadState();
			$state->setName('Suspendu');
			$state->setCode('SUSP');
			$state->setColor('#000000');
			$state->setColorBorder('#000000');
			$state->setIsWon(false);
			$state->setIsLost(true);
			$state->setColorText('#FFFFFF');
			$this->entityManager->persist($state);
			
			$this->entityManager->flush();
		}
		private function createLeadSources(){
			
			if ($this->entityManager->getRepository(LeadSource::class)->findAll()){
				
				return;
			}
			$source = new LeadSource();
			$source->setName('Site Web');
			$source->setCode('WEB');
			$source->setColor('#1160b7');
			$this->entityManager->persist($source);
			
			$source = new LeadSource();
			$source->setName('Appel');
			$source->setCode('CALL');
			$source->setColor('#D24726');
			$this->entityManager->persist($source);
			
			$source = new LeadSource();
			$source->setName('Réseaux Sociaux');
			$source->setCode('SOCIAL');
			$source->setColor('#FF0000');
			$this->entityManager->persist($source);
			
			$source = new LeadSource();
			$source->setName('Autre');
			$source->setCode('OTHER');
			$source->setColor('#4caf50');
			
			$this->entityManager->persist($source);
			$source = new LeadSource();
			$source->setName('Contact personnel');
			$source->setCode('PERSONAL');
			$source->setColor('#4caf50');
			$this->entityManager->persist($source);
			$source = new LeadSource();
			$source->setName('Simulation crédit');
			$source->setCode('SIM');
			$source->setColor('#4caf50');
			$this->entityManager->persist($source);
			
			$this->entityManager->flush();
		}
		private function createInteractionTypes(){
			
			if ($this->entityManager->getRepository(InteractionType::class)->findAll()){
				
				return;
			}
			$type = new InteractionType();
			$type->setName('Appel');
			$type->setCode('CALL');
			$type->setColor('#1160b7');
			$type->setIcone('fa fa-phone');
			$this->entityManager->persist($type);
			
			$type = new InteractionType();
			$type->setName('Email');
			$type->setCode('EMAIL');
			$type->setColor('#D24726');
			$type->setIcone('fa fa-envelope');
			$this->entityManager->persist($type);
			
			$type = new InteractionType();
			$type->setName('Réseaux Sociaux');
			$type->setCode('SOCIAL');
			$type->setColor('#FF0000');
			$type->setIcone('fa fa-facebook');
			$this->entityManager->persist($type);
			
			$type = new InteractionType();
			$type->setName('Autre');
			$type->setCode('OTHER');
			$type->setColor('#4caf50');
			$type->setIcone('fa fa-question');
			
			
			$type = new InteractionType();
			$type->setName('Rendez-vous');
			$type->setCode('RDV');
			$type->setColor('#4caf50');
			$type->setIcone('fa fa-calendar');
			$this->entityManager->persist($type);
			
			$type = new InteractionType();
			$type->setName('Automatique');
			$type->setCode('AUT');
			$type->setColor('#333333');
			$type->setIcone('fa fa-cogs');
			$this->entityManager->persist($type);
			
			$this->entityManager->flush();
		}
		private function createInteractionSources(){
			global $output;
			if ($this->entityManager->getRepository(InteractionSource::class)->findAll()){
				
				return;
			}
			$source = new InteractionSource();
			$source->setName('Phone');
			$source->setCode('PHN');
			$source->setColor('#1160b7');
			$this->entityManager->persist($source);
			
			$source = new InteractionSource();
			$source->setName('Email');
			$source->setCode('EMA');
			$source->setColor('#D24726');
			$this->entityManager->persist($source);
			
			$source = new InteractionSource();
			$source->setName('Messenger');
			$source->setCode('MES');
			$source->setColor('#673888');
			$this->entityManager->persist($source);
			
			$source = new InteractionSource();
			$source->setName('Viber');
			$source->setCode('VIB');
			$source->setColor('#9575cd');
			
			$this->entityManager->persist($source);
			$source = new InteractionSource();
			$source->setName('Rendez-vous');
			$source->setCode('RDV');
			$source->setColor('#ec407a');
			$this->entityManager->persist($source);
			
			$source = new InteractionSource();
			$source->setName('System');
			$source->setCode('SYS');
			$source->setColor('#8bc34a');
			$this->entityManager->persist($source);
			
			$source = new InteractionSource();
			$source->setName('Autre');
			$source->setCode('OTHER');
			$source->setColor('#4caf50');
			$source->setIcone('fa fa-question');
			
			$this->entityManager->flush();
		}
		private function createCustomerStates(){
			global $output;
			if ($this->entityManager->getRepository(CustomerState::class)->findAll()){
				
				return;
			}
			$state = new CustomerState();
			$state->setName('Proposition');
			$state->setCode('PRO');
			$state->setColor('#1160b7');
			$state->setColorBorder('#1160b7');
			$state->setColorText('#FFFFFF');
			
			$this->entityManager->persist($state);
			
			$state = new CustomerState();
			$state->setName('Confirmé');
			$state->setCode('COF');
			$state->setColor('#D24726');
			$state->setColorBorder('#D24726');
			$state->setColorText('#FFFFFF');
			$this->entityManager->persist($state);
			
			$state = new CustomerState();
			$state->setName('Annulé');
			$state->setCode('ANN');
			$state->setColor('#FF0000');
			$state->setColorBorder('#FF0000');
			$state->setColorText('#FFFFFF');
			
			$this->entityManager->persist($state);
			$this->entityManager->flush();
			
		}
		private function createContactJobs(){
			global $output;
			if ($this->entityManager->getRepository(ContactJob::class)->findAll()){
				
				return;
			}
			$job = new ContactJob();
			$job->setName('Employé Secteur Privé');
			$job->setCode('EPR');
			$job->setColor('#1160b7');
			$this->entityManager->persist($job);
			
			$job = new ContactJob();
			$job->setName('Employé Secteur Public');
			$job->setCode('EPB');
			$job->setColor('#D24726');
			$this->entityManager->persist($job);
			
			$job = new ContactJob();
			$job->setName('Cadre');
			$job->setCode('CAD');
			$job->setColor('#ef4f91');
			$this->entityManager->persist($job);
			
			$job = new ContactJob();
			$job->setName('Libéral');
			$job->setCode('LIB');
			$job->setColor('#378b29');
			$this->entityManager->persist($job);
			
			$job = new ContactJob();
			$job->setName('Commerçant');
			$job->setCode('COM');
			$job->setColor('#ff7878');
			$this->entityManager->persist($job);
			
			$job = new ContactJob();
			$job->setName('Chef d\'entreprise');
			$job->setCode('CHE');
			$job->setColor('#00bcd4');
			$this->entityManager->persist($job);
			
			$job = new ContactJob();
			$job->setName('Investisseur');
			$job->setCode('INV');
			$job->setColor('#3f51b5');
			$this->entityManager->persist($job);
			
			$job = new ContactJob();
			$job->setName('Retraité');
			$job->setCode('RET');
			$job->setColor('#795548');
			$this->entityManager->persist($job);
			
			$job = new ContactJob();
			$job->setName('Autre');
			$job->setCode('AUT');
			$job->setColor('#607d8b');
			$this->entityManager->persist($job);
			
			$this->entityManager->flush();
		}
		public function createCbienStates(){
			global $output;
			if ($this->entityManager->getRepository(CbienState::class)->findAll()){
				
				return;
			}
			$state = new CbienState();
			$state->setName('Disponible');
			$state->setCode('DIS');
			$state->setColor('#1160b7');
			$this->entityManager->persist($state);
			
			$state = new CbienState();
			$state->setName('Réservé');
			$state->setCode('RES');
			$state->setColor('#D24726');
			$this->entityManager->persist($state);
			
			$state = new CbienState();
			$state->setName('Vendu');
			$state->setCode('VEN');
			$state->setColor('#FF0000');
			$this->entityManager->persist($state);
			
			$state = new CbienState();
			$state->setName('Livré');
			$state->setCode('LIV');
			$state->setColor('#FF0000');
			$this->entityManager->persist($state);
			
			$this->entityManager->flush();
		}
		public function createChargeTypes(){
			global $output;
			if ($this->entityManager->getRepository(ChargeType::class)->findAll()){
				
				return;
			}
			$type = new ChargeType();
			$type->setName('Exploitation');
			$type->setCode('EXP');
			$type->setColor('#1160b7');
			$this->entityManager->persist($type);
			
			$type = new ChargeType();
			$type->setName('Fonctionnement');
			$type->setCode('FON');
			$type->setColor('#D24726');
			$this->entityManager->persist($type);
			
			$type = new ChargeType();
			$type->setName('Investissement');
			$type->setCode('INV');
			$type->setColor('#FF0000');
			$this->entityManager->persist($type);
			
			$type = new ChargeType();
			$type->setName('Projet');
			$type->setCode('PRO');
			$type->setColor('#378b29');
			$this->entityManager->persist($type);
			
			$this->entityManager->flush();
			
			
			
		}
		public function createCompaignTypes(){
			
			if ($this->entityManager->getRepository(CompaignType::class)->findAll()){
				
				return;
			}
			$type = new CompaignType();
			$type->setName('Emailing');
			$type->setCode('EMAIL');
			$type->setColor('#1160b7');
			$this->entityManager->persist($type);
			
			$type = new CompaignType();
			
			$type->setName('Facebook');
			$type->setCode('FBK');
			$type->setColor('#3575E5');
			$this->entityManager->persist($type);
			
			$type = new CompaignType();
			$type->setName('Instagram');
			$type->setCode('INS');
			$type->setColor('#D24726');
			$this->entityManager->persist($type);
			
			$type = new CompaignType();
			$type->setName('Google');
			$type->setCode('GOO');
			$type->setColor('#FF0000');
			$this->entityManager->persist($type);
			
			$type = new CompaignType();
			$type->setName('TikTok');
			$type->setCode('TIK');
			$type->setColor('#378b29');
			$this->entityManager->persist($type);
			
			$type = new CompaignType();
			$type->setName('Print');
			$type->setCode('PRT');
			$type->setColor('#795548');
			$this->entityManager->persist($type);
			
			$type = new CompaignType();
			$type->setName('Panneau');
			$type->setCode('PAN');
			$type->setColor('#607d8b');
			$this->entityManager->persist($type);
			
			$type = new CompaignType();
			$type->setName('Influencer');
			$type->setCode('INF');
			$type->setColor('#607d8b');
			$this->entityManager->persist($type);
			
			$type = new CompaignType();
			$type->setName('Media');
			$type->setCode('MED');
			$type->setColor('#607d8b');
			$this->entityManager->persist($type);
			
			$type = new CompaignType();
			$type->setName('Autre');
			$type->setCode('AUT');
			$type->setColor('#607d8b');
			$this->entityManager->persist($type);
			
			$this->entityManager->flush();
			
			$this->entityManager->flush();
			
			
			
		}
		public function createCprojectState(){
			global $output;
			if ($this->entityManager->getRepository(CProjectState::class)->findAll()){
				
				return;
			}
			$state = new CProjectState();
			$state->setName('Future');
			$state->setPercentage('0');
			$state->setColor('#1da1f2');
			$this->entityManager->persist($state);
			
			$state = new CProjectState();
			$state->setName('En demarraage');
			$state->setPercentage('5');
			$state->setColor('#0077b5');
			$this->entityManager->persist($state);
			
			$state = new CProjectState();
			$state->setName('Terrassement');
			$state->setPercentage('10');
			$state->setColor('#6f45a9');
			$this->entityManager->persist($state);
			
			$state = new CProjectState();
			$state->setName('Gros Oeuvre');
			$state->setPercentage('30');
			$state->setColor('#e5780b');
			$this->entityManager->persist($state);
			
			$state = new CProjectState();
			$state->setName('Second Oeuvre');
			$state->setPercentage('60');
			$state->setColor('#bc3803');
			$this->entityManager->persist($state);
			
			$state = new CProjectState();
			$state->setName('Finition');
			$state->setPercentage('80');
			$state->setColor('#cc1b00');
			$this->entityManager->persist($state);
			
			$state = new CProjectState();
			$state->setName('Livraison');
			$state->setPercentage('100');
			$state->setColor('#b81800');
			$this->entityManager->persist($state);
			
			$state = new CProjectState();
			$state->setName('Terminé');
			$state->setPercentage('100');
			$state->setColor('#1c6c09');
			$this->entityManager->persist($state);
			
			$this->entityManager->flush();
			
			
			
			
		}
		public function documentTypes(){
			global $output;
			if ($this->entityManager->getRepository(DocumentType::class)->findAll()){
				
				return;
			}
			$type = new DocumentType();
			$type->setName('Facture');
			$type->setCode('FAC');
			$type->setColor('#1160b7');
			$type->setIsProvider(false);
			$type->setIsCustomer(true);
			$type->setIsAffected(false);
			$type->setIsInternal(false);
			$type->setIsProject(false);
			
			$this->entityManager->persist($type);
			
			$type = new DocumentType();
			$type->setName('Ordre de versement');
			$type->setCode('ODV');
			$type->setColor('#D24726');
			$type->setIsProvider(false);
			$type->setIsCustomer(true);
			$type->setIsAffected(false);
			$type->setIsInternal(false);
			$type->setIsProject(false);
			$this->entityManager->persist($type);
			
			$type = new DocumentType();
			$type->setName('Récepicé de versement');
			$type->setCode('RDV');
			$type->setColor('#FF0000');
			$type->setIsProvider(false);
			$type->setIsCustomer(true);
			$type->setIsAffected(false);
			$type->setIsInternal(false);
			$type->setIsProject(false);
			$this->entityManager->persist($type);
			
			$type = new DocumentType();
			$type->setName('Bon de commande');
			$type->setCode('BDC');
			$type->setColor('#378b29');
			$type->setIsProvider(true);
			$type->setIsCustomer(false);
			$type->setIsAffected(false);
			$type->setIsInternal(true);
			$type->setIsProject(true);
			$this->entityManager->persist($type);
			
			$type = new DocumentType();
			$type->setName('Bon de livraison');
			$type->setCode('BDL');
			$type->setColor('#795548');
			$type->setIsProvider(false);
			$type->setIsCustomer(true);
			$type->setIsAffected(false);
			$type->setIsInternal(true);
			$type->setIsProject(false);
			$this->entityManager->persist($type);
			
			$type = new DocumentType();
			$type->setName('Bon de réception');
			$type->setCode('BDR');
			$type->setColor('#607d8b');
			$type->setIsProvider(false);
			$type->setIsCustomer(true);
			$type->setIsAffected(false);
			$type->setIsInternal(false);
			$type->setIsProject(false);
			$this->entityManager->persist($type);
			
			$type = new DocumentType();
			$type->setName('Bon de retour');
			$type->setCode('BRT');
			$type->setColor('#607d8b');
			$type->setIsProvider(false);
			$type->setIsCustomer(true);
			$type->setIsAffected(false);
			$type->setIsInternal(false);
			$type->setIsProject(false);
			$this->entityManager->persist($type);
			
			$type = new DocumentType();
			$type->setName('Carte d\'identité');
			$type->setCode('CIN');
			$type->setColor('#607d8b');
			$type->setIsProvider(true);
			$type->setIsCustomer(true);
			$type->setIsAffected(false);
			$type->setIsInternal(false);
			$type->setIsProject(false);
			$this->entityManager->persist($type);
			
			$type = new DocumentType();
			$type->setName('Acte de naissance');
			$type->setCode('ADN');
			$type->setColor('#607d8b');
			$type->setIsProvider(false);
			$type->setIsCustomer(true);
			$type->setIsAffected(false);
			$type->setIsInternal(false);
			$type->setIsProject(false);
			$this->entityManager->persist($type);
			
			$type = new DocumentType();
			$type->setName('Simulation crédit');
			$type->setCode('SIM');
			$type->setColor('#607d8b');
			$type->setIsProvider(false);
			$type->setIsCustomer(true);
			$type->setIsAffected(false);
			$type->setIsInternal(false);
			$type->setIsProject(false);
			$this->entityManager->persist($type);
			
			$type = new DocumentType();
			$type->setName('Document fournisseur');
			$type->setCode('DOC');
			$type->setColor('#607d8b');
			$type->setIsProvider(true);
			$type->setIsCustomer(false);
			$type->setIsAffected(false);
			$type->setIsInternal(false);
			$type->setIsProject(false);
			$this->entityManager->persist($type);
			
			$type = new DocumentType();
			$type->setName('Autre');
			$type->setCode('AUT');
			$type->setColor('#607d8b');
			$type->setIsProvider(true);
			$type->setIsCustomer(true);
			$type->setIsAffected(false);
			$type->setIsInternal(true);
			$type->setIsProject(true);
			$this->entityManager->persist($type);
			
			$type = new DocumentType();
			$type->setName('Document client');
			$type->setCode('DOC');
			$type->setColor('#607d8b');
			$type->setIsProvider(false);
			$type->setIsCustomer(true);
			$type->setIsAffected(false);
			$type->setIsInternal(false);
			$type->setIsProject(false);
			$this->entityManager->persist($type);
			
			$type = new DocumentType();
			$type->setName('Dossier bancaire');
			$type->setCode('DOB');
			$type->setColor('#607d8b');
			$type->setIsProvider(false);
			$type->setIsCustomer(true);
			$type->setIsAffected(false);
			$type->setIsInternal(false);
			$type->setIsProject(false);
			$this->entityManager->persist($type);
			
			$this->entityManager->flush();
			
			
			
		}
		
		public function createEmployeeJobs(){
			global $output;
			if ($this->entityManager->getRepository(EmplJobs::class)->findAll()){
				
				return;
			}
			$job = new EmplJobs();
			$job->setName('Commercial');
			$this->entityManager->persist($job);
			$job = new EmplJobs();
			$job->setName('Responsable Commercial');
			$this->entityManager->persist($job);
			$job = new EmplJobs();
			$job->setName('Responsable Marketing');
			$this->entityManager->persist($job);
			$job = new EmplJobs();
			$job->setName('Responsable Communication');
			$this->entityManager->persist($job);
			$job = new EmplJobs();
			$job->setName('Responsable Technique');
			$this->entityManager->persist($job);
			$job = new EmplJobs();
			$job->setName('Responsable Administratif');
			$this->entityManager->persist($job);
			$job = new EmplJobs();
			$job->setName('Responsable Financier');
			$this->entityManager->persist($job);
			$job = new EmplJobs();
			$job->setName('Responsable Ressources Humaines');
			$this->entityManager->persist($job);
			$job = new EmplJobs();
			$job->setName('Responsable Informatique');
			$this->entityManager->persist($job);
			$job = new EmplJobs();
			$job->setName('Comptable');
			$this->entityManager->persist($job);
			$job = new EmplJobs();
			$job->setName('Directeur commercial');
			$this->entityManager->persist($job);
			$job = new EmplJobs();
			$job->setName('Directeur Administratif et Financier');
			$this->entityManager->persist($job);
			$job = new EmplJobs();
			$job->setName('Directeur Général');
			$this->entityManager->persist($job);
			$job = new EmplJobs();
			$job->setName('Chef de projet');
			$this->entityManager->persist($job);
			
			$this->entityManager->flush();
			
			
		}
		
		public function createEmployeeRoles(){
			global $output;
			if ($this->entityManager->getRepository(EmplRole::class)->findAll()){
				
				return;
			}
			$role = new EmplRole();
			$role->setRole('ROLE_COMMERCIAL');
			$this->entityManager->persist($role);
			$role = new EmplRole();
			$role->setRole('ROLE_DC');
			$this->entityManager->persist($role);
			$role = new EmplRole();
			$role->setRole('ROLE_FINANCE');
			$this->entityManager->persist($role);
			$role = new EmplRole();
			$role->setRole('ROLE_RH');
			$this->entityManager->persist($role);
			$role = new EmplRole();
			$role->setRole('ROLE_DAG');
			$this->entityManager->persist($role);
			$role = new EmplRole();
			$role->setRole('ROLE_DG');
			$this->entityManager->persist($role);
			$role = new EmplRole();
			$role->setRole('ROLE_ADMIN');
			$this->entityManager->persist($role);
			
			$this->entityManager->flush();
		}
		
		public function createMouvemntTypes(){
			global $output;
			if ($this->entityManager->getRepository(MouvementCompteType::class)->findAll()){
				
				return;
			}
			$type = new MouvementCompteType();
			$type->setName('Entrée');
			$type->setCode('ENT');
			$type->setColor('#1160b7');
			$this->entityManager->persist($type);
			
			$type = new MouvementCompteType();;
			$type->setName('Sortie');
			$type->setCode('SRT');
			$type->setColor('#D24726');
			$this->entityManager->persist($type);
			
			$type = new MouvementCompteType();;
			$type->setName('Transfert');
			$type->setCode('TRF');
			$type->setColor('#FF0000');
			$this->entityManager->persist($type);
			
			$this->entityManager->flush();
		}
		
		public function createPaymentTypes(){
			global $output;
			if ($this->entityManager->getRepository(PaiementType::class)->findAll()){
				
				return;
			}
			$type = new PaiementType();
			$type->setName('Espèce');
			$type->setCode('ESP');
			$type->setColor('#1160b7');
			$this->entityManager->persist($type);
			
			$type = new PaiementType();
			$type->setName('Chèque');
			$type->setCode('CHQ');
			$type->setColor('#D24726');
			$this->entityManager->persist($type);
			
			$type = new PaiementType();
			$type->setName('Virement');
			$type->setCode('VIR');
			$type->setColor('#FF0000');
			$this->entityManager->persist($type);
			
			$type = new PaiementType();
			$type->setName('Versement compte bancaire');
			$type->setCode('VCB');
			$type->setColor('#378b29');
			$this->entityManager->persist($type);
			
			$type = new PaiementType();
			$type->setName('Versement compte caisse');
			$type->setCode('VCC');
			$type->setColor('#795548');
			$this->entityManager->persist($type);
			
			$type = new PaiementType();
			$type->setName('Autre');
			$type->setCode('AUT');
			$type->setColor('#607d8b');
			$this->entityManager->persist($type);
			
			$this->entityManager->flush();
		}
		
		public function createNotificationTypes(){
			global $output;
			if ($this->entityManager->getRepository(\App\Entity\NotificationType::class)->findAll()){
				
				return;
			}
			$type = new NotificationType();
			$type->setName('Email');
			$type->setCode('EMAIL');
			$type->setColor('#1160b7');
			$this->entityManager->persist($type);
			
			$type = new NotificationType();
			$type->setName('SMS');
			$type->setCode('SMS');
			$type->setColor('#D24726');
			$this->entityManager->persist($type);
			
			$type = new NotificationType();
			$type->setName('Notification');
			$type->setCode('NOTIF');
			$type->setColor('#FF0000');
			$this->entityManager->persist($type);
			
			$this->entityManager->flush();
			
		}
		
		public function createOrderStates(){
			global $output;
			if ($this->entityManager->getRepository(\App\Entity\OrderState::class)->findAll()){
				
				return;
			}
			$state = new \App\Entity\OrderState();
			$state->setName('En attente');
			$state->setCode('WAIT');
			$state->setColor('#1160b7');
			
			
			$this->entityManager->persist($state);
			
			$state = new \App\Entity\OrderState();
			$state->setName('En cours');
			$state->setCode('INPROGRESS');
			$state->setColor('#D24726');
			$this->entityManager->persist($state);
			
			$state = new \App\Entity\OrderState();
			$state->setName('Terminé');
			$state->setCode('DONE');
			$state->setColor('#FF0000');
			
			$this->entityManager->persist($state);
			
			
		}
		
		public function createOrderTypes(){
			global $output;
			if ($this->entityManager->getRepository(\App\Entity\OrderType::class)->findAll()){
				
				return;
			}
			$type = new \App\Entity\OrderType();
			$type->setName('Totalité du montant');
			$type->setCode('TOT');
			$type->setColor('#1160b7');
			$type->setTextColor('#FFFFFF');
			$type->setBorderColor('#1160b7');
			$type->setIsBank(false);
			$this->entityManager->persist($type);
			
			$type = new \App\Entity\OrderType();
			$type->setName('Éhéancier');
			$type->setCode('EHE');
			$type->setColor('#D24726');
			$type->setTextColor('#FFFFFF');
			$type->setBorderColor('#D24726');
			$type->setIsBank(false);
			$this->entityManager->persist($type);
			
			$type = new \App\Entity\OrderType();
			$type->setName('Crédit bonifié');
			$type->setCode('CRB');
			$type->setColor('#FF0000');
			$type->setTextColor('#FFFFFF');
			$type->setBorderColor('#FF0000');
			$type->setIsBank(true);
			$this->entityManager->persist($type);
			
			$type = new \App\Entity\OrderType();
			$type->setName('Crédit non bonifié');
			$type->setCode('CRN');
			$type->setColor('#378b29');
			$type->setTextColor('#FFFFFF');
			$type->setBorderColor('#378b29');
			$type->setIsBank(true);
			$this->entityManager->persist($type);
			
			$this->entityManager->flush();
		}
		
		public function createProviderOrderStates(){
			global $output;
			if ($this->entityManager->getRepository(\App\Entity\ProviderOrderState::class)->findAll()){
				
				return;
			}
			$state = new \App\Entity\ProviderOrderState();
			$state->setName('En attente');
			$state->setCode('WAIT');
			$state->setColor('#1160b7');
			$state->setIsFinal(false);
			$this->entityManager->persist($state);
			
			$state = new \App\Entity\ProviderOrderState();
			$state->setName('En cours');
			$state->setCode('INPROGRESS');
			$state->setColor('#D24726');
			$state->setIsFinal(false);
			$this->entityManager->persist($state);
			
			$state = new \App\Entity\ProviderOrderState();
			$state->setName('Terminé');
			$state->setCode('DONE');
			$state->setColor('#FF0000');
			$state->setIsFinal(true);
			$this->entityManager->persist($state);
			
			$this->entityManager->flush();
			
		}
		
		public function createProviderTypes(){
			global $output;
			if ($this->entityManager->getRepository(\App\Entity\ProviderType::class)->findAll()){
				
				return;
			}
			$type = new \App\Entity\ProviderType();
			$type->setName('Autre');
			$type->setCode('Aut');
			$type->setColor('#1160b7');
			$this->entityManager->persist($type);
			
			$type = new \App\Entity\ProviderType();
			$type->setName('Peinture');
			$type->setCode('PEI');
			$type->setColor('#D24726');
			$this->entityManager->persist($type);
			
			$type = new \App\Entity\ProviderType();
			$type->setName('Plomberie');
			$type->setCode('PLO');
			$type->setColor('#FF0000');
			$this->entityManager->persist($type);
			
			$type = new \App\Entity\ProviderType();
			$type->setName('Électricité');
			$type->setCode('ELE');
			$type->setColor('#378b29');
			$this->entityManager->persist($type);
			
			$type = new \App\Entity\ProviderType();
			$type->setName('Menuiserie');
			$type->setCode('MEN');
			$type->setColor('#795548');
			$this->entityManager->persist($type);
			
			$type = new \App\Entity\ProviderType();
			$type->setName('Maçonnerie');
			$type->setCode('MAC');
			$type->setColor('#607d8b');
			$this->entityManager->persist($type);
			
			$type = new \App\Entity\ProviderType();
			$type->setName('Carrelage');
			$type->setCode('CAR');
			$type->setColor('#607d8b');
			$this->entityManager->persist($type);
			
			$type = new \App\Entity\ProviderType();
			$type->setName('Revêtement');
			$type->setCode('REV');
			$type->setColor('#607d8b');
			$this->entityManager->persist($type);
			
			$type = new \App\Entity\ProviderType();
			$type->setName('Cuisine');
			$type->setCode('CUI');
			$type->setColor('#607d8b');
			$this->entityManager->persist($type);
			
			$type = new \App\Entity\ProviderType();
			$type->setName('Location de matériel');
			$type->setCode('LOC');
			$type->setColor('#607d8b');
			$this->entityManager->persist($type);
			
			$type = new \App\Entity\ProviderType();
			$type->setName('Grandes Oeuvres');
			$type->setCode('GRO');
			$type->setColor('#607d8b');
			$this->entityManager->persist($type);
			
			$type = new \App\Entity\ProviderType();
			$type->setName('Secondes Oeuvres');
			$type->setCode('SEC');
			$type->setColor('#607d8b');
			$this->entityManager->persist($type);
			
			$type = new \App\Entity\ProviderType();
			$type->setName('Architecte');
			$type->setCode('ARC');
			$type->setColor('#607d8b');
			$this->entityManager->persist($type);
			
			$this->entityManager->flush();
			
			
			
		}
		
		public function createWebContactState(){
			global $output;
			if ($this->entityManager->getRepository(\App\Entity\WebContactState::class)->findAll()){
				
				return;
			}
			$state = new \App\Entity\WebContactState();
			$state->setName('En attente');
			$state->setCode('WAIT');
			$state->setColor('#1160b7');
			$this->entityManager->persist($state);
			
			$state = new \App\Entity\WebContactState();
			$state->setName('En cours');
			$state->setCode('INPROGRESS');
			$state->setColor('#D24726');
			$this->entityManager->persist($state);
			
			$state = new \App\Entity\WebContactState();
			$state->setName('Terminé');
			$state->setCode('DONE');
			$state->setColor('#FF0000');
			$this->entityManager->persist($state);
			
			$this->entityManager->flush();
		}
		public function createBienAdditionnalState(): void
		{
			global $output;
			if ($this->entityManager->getRepository(\App\Entity\BienAdditionalState::class)->findAll()){
				
				return;
			}
			$state = new \App\Entity\BienAdditionalState();
			$state->setName('Disponible');
			$state->setCode('DIS');
			$state->setColor('#1160b7');
			$this->entityManager->persist($state);
			
			$state = new \App\Entity\BienAdditionalState();
			$state->setName('Resérvé');
			$state->setCode('RES');
			$state->setColor('#D24726');
			$this->entityManager->persist($state);
			
			$state = new \App\Entity\BienAdditionalState();
			$state->setName('Vendus');
			$state->setCode('VND');
			$state->setColor('#FF0000');
			$this->entityManager->persist($state);
			
			$this->entityManager->flush();
		}
		
		public function createBienAdditionnalTypes(): void
		{
			global $output;
			if ($this->entityManager->getRepository(\App\Entity\BienAdditionalType::class)->findAll()){
				
				return;
			}
			$state = new \App\Entity\BienAdditionalType();
			$state->setName('Place de parking Couvert');
			$state->setCode('PAC');
			$state->setColor('#1160b7');
			$this->entityManager->persist($state);
			
			$state = new \App\Entity\BienAdditionalType();
			$state->setName('Place de parking Non couvert');
			$state->setCode('PAN');
			$state->setColor('#D24726');
			$this->entityManager->persist($state);
			
			$state = new \App\Entity\BienAdditionalType();
			$state->setName('Boxe');
			$state->setCode('BOX');
			$state->setColor('#FF0000');
			$this->entityManager->persist($state);
			
			$this->entityManager->flush();
		}
		public function createBankCreditState()
		{
			if ($this->entityManager->getRepository(\App\Entity\BankCreditState::class)->findAll()){
				return;
			}
			$state = new \App\Entity\BankCreditState();
			$state->setName('En attente');
			$state->setCode('WAIT');
			$state->setColor('#1160b7');
			$state->setIsValid(false);
			$this->entityManager->persist($state);
			
			$state = new \App\Entity\BankCreditState();
			$state->setName('Accordé');
			$state->setCode('ACC');
			$state->setColor('#673ab7');
			$state->setIsValid(true);
			$this->entityManager->persist($state);
			
			$state = new \App\Entity\BankCreditState();
			$state->setName('Refusé');
			$state->setCode('REF');
			$state->setColor('#FF0000');
			$state->setIsValid(false);
			$this->entityManager->persist($state);
			
			$state = new \App\Entity\BankCreditState();
			$state->setName('Payé');
			$state->setCode('PAY');
			$state->setColor('#4caf50');
			$state->setIsValid(true);
			$this->entityManager->persist($state);
			
			$this->entityManager->flush();
			
			
			
			
		}
		
		
		
	}