<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Words;

use AL\PhpWndb\Model\Relations\RelationPointerTypeEnum;

class Noun extends WordAbstract implements NounInterface
{
	public function getHypernyms(): array
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::HYPERNYM());
	}

	public function getInstanceHypernyms(): array
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::INSTANCE_HYPERNYM());
	}

	public function getHyponyms(): array
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::HYPONYM());
	}

	public function getInstanceHyponyms(): array
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::INSTANCE_HYPONYM());
	}

	public function getMemberHolonyms(): array
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::MEMBER_HOLONYM());
	}

	public function getSubstanceHolonyms(): array
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::SUBSTANCE_HOLONYM());
	}

	public function getPartHolonyms(): array
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::PART_HOLONYM());
	}

	public function getMemberMeronyms(): array
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::MEMBER_MERONYM());
	}

	public function getSubstanceMeronyms(): array
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::SUBSTANCE_MERONYM());
	}

	public function getPartMeronyms(): array
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::PART_MERONYM());
	}

	public function getAttributes(): array
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::ATTRIBUTE());
	}

	public function getDerivationallyRelatedForms(): array
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::DERIVATIONALLY_RELATED_FORM());
	}

	public function getMemberOfThisDomainTopics(): array
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::MEMBER_OF_THIS_DOMAIN_TOPIC());
	}

	public function getMemberOfThisDomainRegions(): array
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::MEMBER_OF_THIS_DOMAIN_REGION());
	}

	public function getMemberOfThisDomainUsages(): array
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::MEMBER_OF_THIS_DOMAIN_USAGE());
	}
}
