<?php
namespace App\Messenger\MessageHandler;


use App\Entity\Compaign;
use App\Entity\LeadSource;
use App\Entity\TraficTracking;
use App\Messenger\Message\TrackingKernelRequestMessage;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class TrackingKernelRequestHandler
{
	public function __construct(
		private EntityManagerInterface $entityManager,
		private LoggerInterface $logger
	)
	{
	}
	
	public function __invoke(TrackingKernelRequestMessage $message)
{
	try {
		$messageContent = $message->getContent();
		
		$traficTracking = new TraficTracking();
		$traficTracking->setUserAgent($messageContent['userAgent']);
		$traficTracking->setIp($messageContent['ip']);
		$traficTracking->setReferer($messageContent['referer']);
		$traficTracking->setAcceptLanguage($messageContent['acceptLanguage']);
		$traficTracking->setUri($messageContent['uri']);
		$traficTracking->setCookies($messageContent['cookies']);
		$traficTracking->setParams($messageContent['params']);
		$traficTracking->setDateAdd($messageContent['dateAdd']);
		$traficTracking->setPhpsessid($messageContent['phpsessid']);
		$traficTracking->setEvssid($messageContent['EVSSID']);
		$traficTracking->setIsDeleted(false);
		$traficTracking->setUtmCampaign(
			$this->entityManager->getRepository(Compaign::class)->findOneBy(['id'=>$messageContent['utm_campaign']])
		);
		$traficTracking->setUtmSource(
			$this->entityManager->getRepository(LeadSource::class)->findOneBy(['code'=>$messageContent['utm_source']])
		);
		$this->entityManager->persist($traficTracking);
		$this->entityManager->flush();
	} catch (\Exception $e) {
		$this->logger->error($e->getMessage());
	}
}
}