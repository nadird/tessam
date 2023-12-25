<?php
	namespace App\EventListener;
	
	
	
	use App\Messenger\Message\TrackingKernelRequestMessage;
	use Faker\Core\Uuid;
	use Symfony\Component\EventDispatcher\EventSubscriberInterface;
	use Symfony\Component\HttpKernel\Event\RequestEvent;
	use Symfony\Component\HttpKernel\Event\ResponseEvent;
	use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
	use Symfony\Component\Messenger\MessageBusInterface;
	
	class TrackingUuidSubscriber implements EventSubscriberInterface
	{
		public function __construct(
		private MessageBusInterface $bus
		){
		}
		public function onKernelResponse(ResponseEvent $responseEvent){
			$request = $responseEvent->getRequest();
			$response = $responseEvent->getResponse();
			if($response->getStatusCode() === 403){
				throw new AccessDeniedHttpException('Access denied.');
			}
			//dd($response);
			$skippedArray=['/api','_wdt','_profiler','_fragment','images','favicon','icon','uploads','dashboard'];
			$uri = $request->getUri();
			$skippedArray = ['/api', '_wdt', '_profiler', '_fragment', 'images', 'favicon', 'icon', 'uploads', 'dashboard'];
			
			// Check if URI should be skipped
			foreach ($skippedArray as $skip) {
				if (str_contains($uri, $skip)) {
					// Skip the traffic tracking logic
					return;
				}
			}
			$evssid = $request->cookies->get('EVSSID');
			if ($evssid === null) {
				$uuid = new Uuid();
				$evssid = $uuid->uuid3();
				$cookie = new \Symfony\Component\HttpFoundation\Cookie('EVSSID', $evssid, time() + (3600 * 24 * 365));
				$response->headers->setCookie($cookie);
			}
			$traficTrackingArray = [
				'userAgent' => $request->headers->get('User-Agent'),
				'ip' => $request->getClientIp(),
				'referer' => $request->headers->get('referer'),
				'acceptLanguage' => $request->headers->get('accept-language'),
				'uri' => $uri,
				'cookies' => $request->cookies->all(),
				'params' => $request->query->all(),
				'dateAdd' => new \DateTime(),
				'phpsessid' => $request->cookies->get('PHPSESSID'),
				'utm_campaign' => $request->query->get('utm_campaign'),
				'utm_source' => $request->query->get('utm_source'),
				'EVSSID' => $evssid,
			];
			$this->bus->dispatch(new TrackingKernelRequestMessage($traficTrackingArray));
			
		}
		
		public static function getSubscribedEvents(): array
		{
			return[
				ResponseEvent::class => 'onKernelResponse',
			];
		}
	}