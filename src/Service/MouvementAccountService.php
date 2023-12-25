<?php
	
	namespace App\Service;
	
	use App\Entity\MouvementCompte;
	use App\Entity\MouvementCompteType;
	use App\Entity\PaiementHistory;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\String\Slugger\SluggerInterface;
	
	class MouvementAccountService
	{
		public function __construct(private EntityManagerInterface $entityManager)
		{
		}
		
		public function NewMouvementPaiement($id){
			$paiement = $this->entityManager->getRepository(PaiementHistory::class)->find($id);
			$Mouvement = new MouvementCompte();
			$Mouvement->setIsDeleted(false);
			$Mouvement->setType(
				$this->entityManager->getRepository(MouvementCompteType::class)->findOneByCode("ENT")
			);
			$Mouvement->setEmployee($paiement->getEmployee());
			$Mouvement->setDateAdd($paiement->getDateAdd());
			$Mouvement->setPaiementHistory($paiement);
			$Mouvement->setCompteTo($paiement->getCompte());
			$Mouvement->setTotal($paiement->getTotal());
			$Mouvement->setLabel("Paiement de la commande ".$paiement->getCustomer()->getFullname());
			$Mouvement->setNote(
				"Commande ".$paiement->getCustomer()->getFullname()." - ".$paiement->getOrderPaiement()->getId()
			);
			$this->entityManager->persist($Mouvement);
			$this->entityManager->flush();
			return true;
			
		}
	}