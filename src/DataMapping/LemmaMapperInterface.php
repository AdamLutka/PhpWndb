<?php
declare(strict_types=1);

namespace AL\PhpWndb\DataMapping;

interface LemmaMapperInterface
{
	public function lemmaToToken(string $lemma): string;

	public function tokenToLemma(string $token): string;
}
