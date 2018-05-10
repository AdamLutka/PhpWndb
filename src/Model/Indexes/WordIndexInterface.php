<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Indexes;

use AL\PhpWndb\Model\Relations\RelationPointerTypeEnum;
use AL\PhpWndb\PartOfSpeechEnum;

interface WordIndexInterface
{
	public function getLemma(): string;

	public function getPartOfSpeech(): PartOfSpeechEnum;

	/**
	 * @return RelationPointerTypeEnum[]
	 */
	public function getRelationPointerTypes(): array;

	/**
	 * @return int[]
	 */
	public function getSynsetOffsets(): array;
}
