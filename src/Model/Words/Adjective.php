<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Words;

use AL\PhpWndb\Model\Relations\RelationPointerTypeEnum;

class Adjective extends WordAbstract implements AdjectiveInterface
{
	public function getSimilarTo(): iterable
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::SIMILAR_TO());
	}

	public function getParticipleOfVerbs(): iterable
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::PARTICIPLE_OF_VERB());
	}

	public function getPertainyms(): iterable
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::PERTAINYM());
	}

	public function getAttributes(): iterable
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::ATTRIBUTE());
	}

	public function getAlsoSee(): iterable
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::ALSO_SEE());
	}
}
