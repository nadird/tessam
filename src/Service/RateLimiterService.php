<?php
	namespace App\Service;
	
	use Symfony\Component\HttpFoundation\Request;
	
	class RateLimiterService
	{
		private array $requestCounts;
		private int $maxRequests;
		private int $resetTime;
		
		public function __construct(int $maxRequests = 200, int $resetTime = 3600)
		{
			$this->requestCounts = [];
			$this->maxRequests = $maxRequests; // Max requests allowed per IP per resetTime
			$this->resetTime = $resetTime; // Time in seconds to reset the count
		}
		
		public function isAllowed(Request $request): bool
		{
			$clientIp = $request->getClientIp();
			
			if (!isset($this->requestCounts[$clientIp])) {
				$this->requestCounts[$clientIp] = ['count' => 1, 'resetTime' => time() + $this->resetTime];
				return true;
			}
			
			$data = &$this->requestCounts[$clientIp];
			
			if (time() > $data['resetTime']) {
				// Reset the count and resetTime
				$data['count'] = 1;
				$data['resetTime'] = time() + $this->resetTime;
				return true;
			}
			
			if ($data['count'] < $this->maxRequests) {
				// Increment the count and allow the request
				$data['count']++;
				return true;
			}
			
			// Deny the request
			return false;
		}
	}
