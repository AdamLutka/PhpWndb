<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Words;

use AL\PhpWndb\Model\Relations\RelationPointerTypeEnum;

class Adverb extends WordAbstract implements AdverbInterface
{
	public function getDerivedFromAdjectives(): array
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::DERIVED_FROM_ADJECTIVE());
	}
}
