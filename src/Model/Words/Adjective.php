<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Words;

use AL\PhpWndb\Model\Relations\RelationPointerTypeEnum;

class Adjective extends WordAbstract implements AdjectiveInterface
{
	public function getSimilarTo(): array
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::SIMILAR_TO());
	}

	public function getParticipleOfVerbs(): array
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::PARTICIPLE_OF_VERB());
	}

	public function getPertainyms(): array
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::PERTAINYM());
	}

	public function getAttributes(): array
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::ATTRIBUTE());
	}

	public function getAlsoSee(): array
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::ALSO_SEE());
	}
}
