<?php
declare(strict_types=1);

namespace AL\PhpWndb\Repositories;

use AL\PhpWndb\Model\Indexes\WordIndexInterface;
use AL\PhpWndb\PartOfSpeechEnum;

interface WordIndexMultiRepositoryInterface extends WordIndexRepositoryInterface
{
	/**
	 * @throws UnexpectedValueException
	 */
	public function findWordIndexByPartOfSpeech(PartOfSpeechEnum $partOfSpeech, string $lemma): ?WordIndexInterface;
}
