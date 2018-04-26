<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Synsets;

use AL\PhpWndb\Model\Words\WordInterface;
use AL\PhpWndb\PartOfSpeechEnum;

interface SynsetInterface
{
	public function getSynsetOffset(): int;

	public function getGloss(): string;

	/**
	 * @return WordInterface[]
	 */
	public function getWords(): iterable;

	public function getPartOfSpeech(): PartOfSpeechEnum;
}
