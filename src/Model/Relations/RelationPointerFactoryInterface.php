<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Relations;

use AL\PhpWndb\PartOfSpeechEnum;

interface RelationPointerFactoryInterface
{
	public function createRelationPointer(
		RelationPointerTypeEnum $pointerType,
		PartOfSpeechEnum $targetPartOfSpeech,
		int $targetSynsetOffset,
		?int $targetWordIndex
	): RelationPointerInterface;
}
