<?php
	// src/Command/CreateUserCommand.php
	namespace App\Command;
	
	use App\Entity\Cbien;
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
	use Symfony\Component\String\Slugger\SluggerInterface;
	use Symfony\Component\Uid\Uuid;
	use function PHPUnit\Framework\isInstanceOf;
	use function PHPUnit\Framework\isType;

// the name of the command is what users type after "php bin/console"
	#[AsCommand(name: 'app:createTypesReal', description: 'Arrange Areas ',)]
	class CreateTypesRealEstate extends Command
	{
		public function __construct(
			private \Doctrine\ORM\EntityManagerInterface $entityManager,
			ParameterBagInterface $params,
			private \App\Service\ImageUploadService $imageUploadService,
			private SluggerInterface $slugger
		
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
			
			$pathdefault = $this->params->get('kernel.project_dir').'/public/images/default';
			
			$type= new \App\Entity\CTypeBien();
			$type->setName('Appartement');
			$type->setText("
			<p><b>Découvrez nos appartements :</b> Nous comprenons que chaque client est unique, et c'est pourquoi nous offrons une large gamme d'appartements adaptés à divers besoins et styles de vie. Que vous cherchiez un cosy studio, un spacieux appartement familial, ou une résidence de luxe, nous avons ce qu'il vous faut.
            </p>
               <p><b> Qualité et Confort : </b>Nos appartements sont conçus avec le souci du détail. Chaque espace est optimisé pour offrir confort et fonctionnalité, sans compromis sur le style. Des cuisines modernes équipées aux salles de bains élégantes, chaque élément est choisi pour assurer votre confort et satisfaction.
                </p>
               <p><b> Emplacements de choix :</b> Situés dans des quartiers recherchés, nos appartements vous offrent non seulement un cadre de vie exceptionnel, mais aussi un accès facile aux commodités urbaines : commerces, écoles, transports en commun, parcs et plus encore.
                </p>
               <p><b> Sécurité et tranquillité</b> d'esprit : La sécurité est une priorité chez [Nom de l'Entreprise]. Nos résidences sont équipées de systèmes de sécurité modernes, incluant vidéosurveillance et contrôles d'accès, pour garantir votre tranquillité d'esprit.
                </p>
               <p><b> Un engagement écologique :</b> Conscients de notre responsabilité environnementale, nos projets immobiliers sont conçus pour être éco-responsables, avec des matériaux durables et des solutions d'économie d'énergie.
                </p>
               <p><b> Accompagnement personnalisé :</b> Notre équipe dédiée est là pour vous accompagner à chaque étape, de la visite initiale à la signature du contrat. Nous nous engageons à fournir un service personnalisé pour répondre à vos besoins spécifiques.
                </p>
               <p> Venez découvrir nos appartements et laissez-nous vous aider à trouver le lieu idéal où vous pourrez créer des souvenirs inoubliables.</p>
			");
			$type->setShortDescription('Appartement');
			$type->setSlug('appartements');
			$type->setPublicTitle('Appartement Simplex');
			$type->setIsActive(true);
			$type->setThumb(
				$this->uploadImage($pathdefault.'/Simplex_T.jpg')
			);
			$type->setImage(
				$this->uploadImage($pathdefault.'/Simplex_I.jpeg')
			);
			
			$this->entityManager->persist($type);
			
			
			$type= new \App\Entity\CTypeBien();
			$type->setName('Duplex');
			$type->setText("
			   <p><b> Espace et style :</b> Nos duplex sont conçus pour ceux qui cherchent un équilibre parfait entre l'espace généreux d'une maison et le confort moderne d'un appartement. Avec des designs ouverts, des finitions haut de gamme et des vues imprenables, nos duplex offrent un cadre de vie unique et raffiné.
   </p>
   <p><b> Double le confort :</b> Profitez de la disposition sur deux étages, offrant des espaces de vie distincts et privés. Idéal pour les familles ou pour ceux qui aiment recevoir, nos duplex permettent une séparation confortable entre les espaces communs et les zones de repos.
    </p>
    <p><b>Situations privilégiées :</b> Nos duplex se trouvent dans des emplacements recherchés, combinant le calme résidentiel et la proximité des commodités urbaines. Profitez d'un cadre de vie tranquille tout en étant à quelques pas de tout ce dont vous avez besoin.
    </p>
			");
			$type->setShortDescription('Duplex');
			$type->setSlug('duplex');
			$type->setPublicTitle('Appartement Duplex');
			$type->setIsActive(true);
			$type->setThumb(
				$this->uploadImage($pathdefault.'/Duplex_T.jpg')
			);
			$type->setImage(
				$this->uploadImage($pathdefault.'/Duplex_I.jpeg')
			);
			
			$this->entityManager->persist($type);
			
			$type= new \App\Entity\CTypeBien();
			$type->setName('Villa');
			$type->setText("
			   <p><b> Villas exclusives : </b>Nos villas sont synonymes de luxe et d'exclusivité. Chaque villa est une œuvre d'art architecturale, offrant intimité, espace et design de pointe.
</p>
<p><b>Des aménagements personnalisés :</b> Avec de vastes jardins, des piscines privées et des intérieurs personnalisables, nos villas sont conçues pour répondre à vos désirs les plus exigeants.
</p>
<p><b>Un cadre de vie idyllique :</b> Situées dans des environnements paisibles et verdoyants, nos villas offrent une échappée belle loin de l'agitation de la ville, tout en restant à proximité des centres urbains.
     </p>");
			$type->setShortDescription('Villa');
			$type->setSlug('villa');
			$type->setPublicTitle('Villa');
			$type->setIsActive(true);
			$type->setThumb(
				$this->uploadImage($pathdefault.'/Villa_T.jpg')
			);
			$type->setImage(
				$this->uploadImage($pathdefault.'/Villa_I.jpeg')
			);
			$this->entityManager->persist($type);
			
			$type= new \App\Entity\CTypeBien();
			$type->setName('Locaux Commerciaux');
			$type->setText("<p><b>Locaux adaptés à votre entreprise :</b> Que vous soyez un détaillant, un restaurateur ou un entrepreneur, nos locaux commerciaux sont conçus pour répondre à vos besoins spécifiques.
</p>
   <p> <b> Emplacements stratégiques :</b> Situés dans des zones à fort trafic, nos locaux commerciaux offrent une visibilité maximale et un accès facile pour vos clients.
</p>
    <p> <b>Flexibilité et modernité :</b> Nos espaces sont modulables pour s'adapter à divers types d'activités commerciales, tout en offrant des infrastructures modernes et efficaces. </p>
 			");
			$type->setShortDescription('Locaux Commerciaux');
			$type->setSlug('locaux_commerciaux');
			$type->setPublicTitle('Locaux Commerciaux');
			$type->setIsActive(true);
			$type->setThumb(
				$this->uploadImage($pathdefault.'/Commercial_T.jpg')
			);
			$type->setImage(
				$this->uploadImage($pathdefault.'/Commercial_I.jpeg')
			);
			$this->entityManager->persist($type);
			
			$type= new \App\Entity\CTypeBien();
			$type->setName('Locaux de service');
			$type->setText("<p><b>
    Despaces polyvalents : </b>Nos locaux de service sont parfaits pour les professionnels à la recherche d'espaces flexibles et fonctionnels. Que ce soit pour des bureaux, des ateliers ou des espaces de stockage, nous avons ce qu'il vous faut.
   </p>
   <p> <b>Conçus pour la productivité :</b> Avec des aménagements pratiques et des services adaptés, nos locaux sont conçus pour optimiser votre productivité et votre efficacité.
</p><p>
   <b> Localisations idéales :</b> Facilement accessibles et bien situés, nos locaux de service vous assurent une proximité avec vos clients et partenaires.
</p>");
			$type->setShortDescription('Locaux de service');
			$type->setSlug('locaux_de_service');
			$type->setPublicTitle('Locaux de service');
			$type->setIsActive(true);
			$type->setThumb(
				$this->uploadImage($pathdefault.'/Service_T.jpg')
			);
			$type->setImage(
				$this->uploadImage($pathdefault.'/Service_I.jpeg')
			);
			$this->entityManager->persist($type);
			
			$this->entityManager->flush();
			return Command::SUCCESS;
			
		}
		
		
		
		private function uploadImage($image){
			$fileSystem = new \Symfony\Component\Filesystem\Filesystem();
			$pathtoSave = $this->params->get('public_directory');
			$uploadedFile = new \Symfony\Component\HttpFoundation\File\File($image);
			$originalFilename = pathinfo($uploadedFile->getFilename(), PATHINFO_FILENAME);
			$safeFilename = $this->slugger->slug($originalFilename);
			$newFilename = $safeFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
			$newPath = $pathtoSave . '/' . $newFilename;
			
			// Use the Filesystem component to copy the file
			$fileSystem->copy($uploadedFile->getPathname(), $newPath);
			
			return $newFilename;
		}
		
		
		
		
		
		
		
		
	}