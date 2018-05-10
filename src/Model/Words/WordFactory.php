<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Words;

use AL\PhpWndb\Model\Relations\RelationsInterface;

class WordFactory implements WordFactoryInterface
{
	public function createAdjective(string $lemma, int $lexId, RelationsInterface $relations): AdjectiveInterface
	{
		return new Adjective($lemma, $lexId, $relations);
	}

	public function createAdverb(string $lemma, int $lexId, RelationsInterface $relations): AdverbInterface
	{
		return new Adverb($lemma, $lexId, $relations);
	}

	public function createNoun(string $lemma, int $lexId, RelationsInterface $relations): NounInterface
	{
		return new Noun($lemma, $lexId, $relations);
	}

	/**
	 * @param int[] $frames
	 */
	public function createVerb(string $lemma, int $lexId, RelationsInterface $relations, array $frames): VerbInterface
	{
		return new Verb($lemma, $lexId, $relations, $frames);
	}
}
