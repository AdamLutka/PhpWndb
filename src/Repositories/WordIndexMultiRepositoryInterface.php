<?php
declare(strict_types=1);

namespace AL\PhpWndb\Repositories;

use AL\PhpWndb\Model\Indices\WordIndexInterface;
use AL\PhpWndb\PartOfSpeechEnum;

interface WordIndexMultiRepositoryInterface extends WordIndexRepositoryInterface
{
	/**
	 * @return WordIndexInterface[]
	 */
	public function findAllWordIndices(string $lemma): array;

	/**
	 * @throws \UnexpectedValueException
	 */
	public function findWordIndexByPartOfSpeech(PartOfSpeechEnum $partOfSpeech, string $lemma): ?WordIndexInterface;
}
