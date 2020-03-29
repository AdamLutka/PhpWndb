<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Synsets;

use AL\PhpEnum\Enum;
use AL\PhpWndb\Model\Words\WordInterface;
use AL\PhpWndb\PartOfSpeechEnum;

interface SynsetInterface
{
	public function getSynsetOffset(): int;

	public function getGloss(): string;

	/**
	 * @return Enum
	 */
	public function getSynsetCategory();

	/**
	 * @return WordInterface[]
	 */
	public function getWords(): array;

	public function getPartOfSpeech(): PartOfSpeechEnum;
}
