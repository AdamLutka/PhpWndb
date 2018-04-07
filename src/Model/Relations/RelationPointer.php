<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Relations;

use AL\PhpWndb\PartOfSpeechEnum;

class RelationPointer implements RelationPointerInterface
{
	/** @var RelationPointerTypeEnum */
	private $pointerType;

	/** @var PartOfSpeechEnum */
	private $targetPartOfSpeech;

	/** @var int */
	private $targetSynsetOffset;

	/** @var int|null */
	private $targetWordIndex;


	public function __construct(RelationPointerTypeEnum $pointerType, PartOfSpeechEnum $targetPartOfSpeech, int $targetSynsetOffset, ?int $targetWordIndex)
	{
		$this->pointerType = $pointerType;
		$this->targetPartOfSpeech = $targetPartOfSpeech;
		$this->targetSynsetOffset = $targetSynsetOffset;
		$this->targetWordIndex = $targetWordIndex;
	}


	public function getPointerType(): RelationPointerTypeEnum
	{
		return $this->pointerType;
	}

	public function getTargetPartOfSpeech(): PartOfSpeechEnum
	{
		return $this->targetPartOfSpeech;
	}

	public function getTargetSynsetOffset(): int
	{
		return $this->targetSynsetOffset;
	}

	public function getTargetWordIndex(): ?int
	{
		return $this->targetWordIndex;
	}
}
