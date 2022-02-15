<?php declare(strict_types=1);
namespace tsjost\Tradera;

interface HttpRequest
{
	public function get(string $url): string;
}
