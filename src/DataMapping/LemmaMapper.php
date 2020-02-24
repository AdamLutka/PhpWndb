<?php
declare(strict_types=1);

namespace AL\PhpWndb\DataMapping;

use AL\PhpWndb\Cache\CacheInterface;

class LemmaMapper implements LemmaMapperInterface
{
	/** @var CacheInterface */
	private $cache;


	public function __construct(CacheInterface $cache)
	{
		$this->cache = $cache;
	}


	public function lemmaToToken(string $lemma): string
	{
		$key = 'lemmaToToken_' . $lemma;

		return $this->getFromCache($key, function () use ($lemma) {
			return str_replace(' ', '_', strtolower($lemma));
		});
	}

	public function tokenToLemma(string $token): string
	{
		$key = 'tokenToLemma_' . $token;

		return $this->getFromCache($key, function () use ($token) {
			return str_replace('_', ' ', $token);
		});
	}



	private function getFromCache(string $key, callable $valueFactory): string
	{
		$value = $this->cache->get($key);

		if ($value === null) {
			$value = $valueFactory();
			$this->cache->set($key, $value);
		}

		return $value;
	}
}
