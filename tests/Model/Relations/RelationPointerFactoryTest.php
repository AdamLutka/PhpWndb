<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests\Synsets\Relations;

use AL\PhpWndb\Model\Relations\RelationPointerInterface;
use AL\PhpWndb\Model\Relations\RelationPointerFactory;
use AL\PhpWndb\Model\Relations\RelationPointerTypeEnum;
use AL\PhpWndb\Tests\AbstractBaseTest;
use AL\PhpWndb\PartOfSpeechEnum;

class RelationPointerFactoryTest extends AbstractBaseTest
{
	public function testCreateRelationPointer(): void
	{
		$pointerType = RelationPointerTypeEnum::HYPONYM();
		$targetPartOfSpeech = PartOfSpeechEnum::NOUN();
		$targetSynsetOffset = 1005;
		$targetWordIndex = 30;

		$factory = new RelationPointerFactory();
		$relationPointer = $factory->createRelationPointer(
			$pointerType,
			$targetPartOfSpeech,
			$targetSynsetOffset,
			$targetWordIndex
		);

		static::assertInstanceOf(RelationPointerInterface::class, $relationPointer);
		static::assertEnum($pointerType, $relationPointer->getPointerType());
		static::assertEnum($targetPartOfSpeech, $relationPointer->getTargetPartOfSpeech());
		static::assertSame($targetSynsetOffset, $relationPointer->getTargetSynsetOffset());
		static::assertSame($targetWordIndex, $relationPointer->getTargetWordIndex());
	}
}
