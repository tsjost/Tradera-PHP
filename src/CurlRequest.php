<?php declare(strict_types=1);
namespace tsjost\Tradera;

class CurlRequest implements HttpRequest
{
	public function get(string $url): string
	{
		$c = curl_init($url);
		$o = [
			CURLOPT_RETURNTRANSFER => true,
		];
		curl_setopt_array($c, $o);
		$ret = curl_exec($c);
		curl_close($c);

		return $ret;
	}
}
