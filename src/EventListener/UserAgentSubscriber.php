<?php
	namespace App\EventListener;
	
	use App\Entity\Compaign;
	use App\Entity\LeadSource;
	use App\Entity\TraficTracking;
	use App\Messenger\Message\TrackingKernelRequestMessage;
	use App\Service\RateLimiterService;
	use App\Service\UserAgentParseService;
	use App\Service\WafSecurityAnalyserService;
	use Doctrine\ORM\EntityManagerInterface;
	use Faker\Core\Uuid;
	use Psr\Log\LoggerInterface;
	use Symfony\Component\DependencyInjection\ContainerInterface;
	use Symfony\Component\EventDispatcher\EventSubscriberInterface;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\HttpKernel\Event\RequestEvent;
	use Symfony\Component\HttpKernel\Event\ResponseEvent;
	use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
	use Symfony\Component\Messenger\MessageBusInterface;
	
	class UserAgentSubscriber implements EventSubscriberInterface
	{
		public function __construct(
			// private MessageBusInterface $bus,
			//private ContainerInterface $container
			private LoggerInterface $logger,
			private RateLimiterService $rateLimiterService,
			private WafSecurityAnalyserService $wafSecurityAnalyserService,
			private UserAgentParseService $agentParseService
			
			
		){
		
		}
		
		public function onKernelRequest(RequestEvent $requestEvent)
		{
			$request=$requestEvent->getRequest();
			//$skippedArray=['/api','_wdt','_profiler','_fragment','images','favicon','icon','uploads','dashboard'];
			//$uri = $request->getUri();
			//$skippedArray = ['/api', '_wdt', '_profiler', '_fragment', 'images', 'favicon', 'icon', 'uploads', 'dashboard'];
			
			
			
			if(!$this->agentParseService->analyseUserAgent($requestEvent->getRequest()->headers->get('User-Agent'))){
				$this->denyAccess($requestEvent,'Bad user agent.');
			}
			if(!$this->performSecurityChecks($request)){
				$this->denyAccess($requestEvent, 'Security checks failed');
			}
			
			if(!$this->rateLimiterService->isAllowed($request)){
				$this->denyAccess($requestEvent, 'Too many requests. ');
			}
			if(!$this->wafSecurityAnalyserService->analyzeRequestParameters($request->query->all())){
				$this->denyAccess($requestEvent, 'Potential threat detected. ');
			}
		}
		private function performSecurityChecks($request): bool
		{
			$uri = $request->getUri();
			$host = $request->getHost();
			$envHost = $_ENV['WEBSITE_HOST'];
			
			if(
				$host !== $_ENV['WEBSITE_HOST'] ||
				$request->headers->get('User-Agent') == null ||
				$request->getClientIp() == null ||
				$request->getUri() == null ||
				$request->headers->get('accept-language') == null ||
				$_ENV['WEBSITE_HTTPS'] ==1 && $request->getScheme() !=='https'
			){
				$this->logger->info('Access.Denied', ['reason'=>'somthing is null','host' => $host, 'uri' => $uri, 'ip' => $request->getClientIp(), 'userAgent' => $request->headers->get('User-Agent')]);
				return false;
			}
			$uri = strtolower($uri);
			if(
				str_contains($uri, 'index.php')||
				str_contains($uri, '.env')||
				str_contains($uri, 'wp-admin')||
				str_contains($uri, 'wp-login')||
				str_contains($uri, 'wp-content')||
				str_contains($uri, 'wp-includes')||
				str_contains($uri, 'wp-json')||
				str_contains($uri, 'admin')||
				str_contains($uri, 'wp-')||
				str_contains($uri, '.git')||
				str_contains($uri, 'script')||
				str_contains($uri, 'xmlrpc')||
				str_contains($uri, 'xmlrpc.php')||
				str_contains($uri, 'xmlrpc.php?rsd')
			){
				$this->logger->info('Access.Denied', ['reason'=>'uri contains bad chit ','host' => $host, 'envHost' => $envHost, 'uri' => $uri, 'ip' => $request->getClientIp(), 'userAgent' => $request->headers->get('User-Agent')]);
				return false;
			}
			
			return true;
		}
		private function denyAccess(RequestEvent $requestEvent, string $reason): void
		{
			$this->logger->info('Access.Denied', [
				'reason' => $reason,
				'uri' => $requestEvent->getRequest()->getUri(),
				'ip' => $requestEvent->getRequest()->getClientIp(),
				'userAgent' => $requestEvent->getRequest()->headers->get('User-Agent')
			]);
			
			$requestEvent->setResponse(new Response('Access denied.', 403));
			throw new AccessDeniedHttpException('Access denied.');
		}
		public static function getSubscribedEvents(): array
		{
			return[
				RequestEvent::class => 'onKernelRequest',
			];
		}
	}