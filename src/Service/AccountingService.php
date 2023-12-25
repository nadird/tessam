<?php
	
	namespace App\Service;
	
	
	
	use App\Entity\MouvementCompte;
	use App\Entity\MouvementCompteType;
	use App\Repository\ChargeHistoryRepository;
	use App\Repository\MouvementCompteRepository;
	use App\Repository\PaiementHistoryRepository;
	use Doctrine\ORM\EntityManagerInterface;
	
	class AccountingService
	{
		public function __construct(
			private ChargeHistoryRepository $chargeHistoryRepository,
			private EntityManagerInterface    $entityManager,
			private PaiementHistoryRepository $paiementHistoryRepository,
			private MouvementCompteRepository $mouvementCompteRepository,
		){
		}
		public function newMouvementCharge($id){
			$charge = $this->chargeHistoryRepository->findOneById($id);
			$mouvement = new MouvementCompte();
			$mouvement->setChargeHistory($charge);
			$mouvement->setDateAdd(new \DateTime());
			$mouvement->setEmployee($charge->getEmployee());
			$mouvement->setIsDeleted(false);
			$mouvement->setCompteFrom($charge->getCompte());
			$mouvement->setCompteTo(null);
			$mouvement->setTotal($charge->getTotal());
			$mouvement->setType(
				$this->entityManager->getRepository(MouvementCompteType::class)->findOneBy(['code'=>'SRT'])
			);
			$mouvement->setLabel($charge->getLabel());
			$mouvement->setNote($charge->getNote());
			$this->entityManager->persist($mouvement);
			$this->entityManager->flush();
			return true;
		}
		public function newMouvementPaiement($id){
			$paiement = $this->paiementHistoryRepository->findOneById($id);
			$mouvement = new MouvementCompte();
			$mouvement->setPaiementHistory($paiement);
			$mouvement->setDateAdd(new \DateTime());
			$mouvement->setEmployee($paiement->getEmployee());
			$mouvement->setIsDeleted(false);
			$mouvement->setCompteFrom($paiement->getCompte());
			$mouvement->setCompteTo($paiement->getCompte());
			$mouvement->setTotal($paiement->getTotal());
			$mouvement->setType(
				$this->entityManager->getRepository(MouvementCompteType::class)->findOneBy(['code'=>'PAI'])
			);
			$mouvement->setLabel('Paiement'.$paiement->getOrderPaiement()->getCustomer().' ');
			$mouvement->setNote($paiement->getNote());
			$this->entityManager->persist($mouvement);
			$this->entityManager->flush();
			return true;
		}
		
		
	}
