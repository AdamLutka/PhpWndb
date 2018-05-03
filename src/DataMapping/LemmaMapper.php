<?php
declare(strict_types=1);

namespace AL\PhpWndb\DataMapping;

class LemmaMapper implements LemmaMapperInterface
{
	public function lemmaToToken(string $lemma): string
	{
		return str_replace(' ', '_', strtolower($lemma));
	}

	public function tokenToLemma(string $token): string
	{
		return str_replace('_', ' ', $token);
	}
}
