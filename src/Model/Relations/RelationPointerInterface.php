<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Relations;

use AL\PhpWndb\PartOfSpeechEnum;

interface RelationPointerInterface
{
	public function getPointerType(): RelationPointerTypeEnum;

	public function getTargetPartOfSpeech(): PartOfSpeechEnum;

	public function getTargetSynsetOffset(): int;
	
	public function getTargetWordIndex(): ?int;
}
