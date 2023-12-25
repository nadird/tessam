<?php
	
	namespace App\Messenger\Message;
	
	class TrackingKernelRequestMessage
	{
		public function __construct(
			private array $content,
		)
		{
		}
		
		public function getContent(): array
		{
			return $this->content;
		}
	}