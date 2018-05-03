<?php
declare(strict_types=1);

namespace AL\PhpWndb\DataMapping;

use AL\PhpWndb\Model\Relations\RelationPointerTypeEnum;
use AL\PhpWndb\PartOfSpeechEnum;

interface RelationPointerTypeMapperInterface
{
	public function tokenToRelationPointerType(string $token, PartOfSpeechEnum $sourcePartOfSpeech): RelationPointerTypeEnum;
}
