<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Words;

use AL\PhpWndb\Model\Relations\RelationsInterface;
use AL\PhpWndb\Model\Words\AdjectiveInterface;
use AL\PhpWndb\Model\Words\AdverbInterface;
use AL\PhpWndb\Model\Words\NounInterface;
use AL\PhpWndb\Model\Words\VerbInterface;

interface WordFactoryInterface
{
	public function createAdjective(
		string $lemma,
		int $lexId,
		RelationsInterface $relations
	): AdjectiveInterface;

	public function createAdverb(
		string $lemma,
		int $lexId,
		RelationsInterface $relations
	): AdverbInterface;

	public function createNoun(
		string $lemma,
		int $lexId,
		RelationsInterface $relations
	): NounInterface;

	/**
	 * @param int[] $frames
	 */
	public function createVerb(
		string $lemma,
		int $lexId,
		RelationsInterface $relations,
		array $frames
	): VerbInterface;
}
