<?php
// src/Twig/PathInfoExtension.php
namespace App\Twigextensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class PathInfoExtension extends AbstractExtension
{
	public function getFilters(): array
	{
		return [
			new TwigFilter('pathinfo', [$this, 'pathInfo']),
		];
	}
	
	public function pathInfo($path, $option = null)
	{
		$info = pathinfo($path);
		if ($option !== null && array_key_exists($option, $info)) {
			return $info[$option];
		}
		
		return $info;
	}
}
