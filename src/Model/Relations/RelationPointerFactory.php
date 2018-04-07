<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Relations;

use AL\PhpWndb\PartOfSpeechEnum;

class RelationPointerFactory implements RelationPointerFactoryInterface
{
	public function createRelationPointer(
		RelationPointerTypeEnum $pointerType,
		PartOfSpeechEnum $targetPartOfSpeech,
		int $targetSynsetOffset,
		?int $targetWordIndex
	): RelationPointerInterface
	{
		return new RelationPointer($pointerType, $targetPartOfSpeech, $targetSynsetOffset, $targetWordIndex);
	}
}
