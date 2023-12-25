<?php
	namespace App\Service;
	
	class IpBlockedCheckService
	{
		private array $blockedIps;
		private array $allowedIps;
		
		public function __construct(array $blockedIps = [], array $allowedIps = [])
		{
			$this->blockedIps = $blockedIps;
			$this->allowedIps = $allowedIps;
		}
		
		public function isBlocked(string $ipAddress): bool
		{
			// Check if the IP is explicitly allowed
			if (in_array($ipAddress, $this->allowedIps)) {
				return false;
			}
			
			// Check if the IP is blocked
			return in_array($ipAddress, $this->blockedIps);
		}
		
		public function addBlockedIp(string $ipAddress): void
		{
			if (!in_array($ipAddress, $this->blockedIps)) {
				$this->blockedIps[] = $ipAddress;
			}
		}
		
		public function removeBlockedIp(string $ipAddress): void
		{
			$this->blockedIps = array_filter($this->blockedIps, function ($ip) use ($ipAddress) {
				return $ip !== $ipAddress;
			});
		}
		
		// Optionally, implement methods to manage allowed IPs similarly
	}