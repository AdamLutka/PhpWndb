<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Words;

use AL\PhpWndb\Model\Relations\RelationPointerTypeEnum;

class Noun extends WordAbstract implements NounInterface
{
	public function getHypernyms(): iterable
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::HYPERNYM());
	}

	public function getInstanceHypernyms(): iterable
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::INSTANCE_HYPERNYM());
	}

	public function getHyponyms(): iterable
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::HYPONYM());
	}

	public function getInstanceHyponyms(): iterable
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::INSTANCE_HYPONYM());
	}

	public function getMemberHolonyms(): iterable
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::MEMBER_HOLONYM());
	}

	public function getSubstanceHolonyms(): iterable
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::SUBSTANCE_HOLONYM());
	}

	public function getPartHolonyms(): iterable
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::PART_HOLONYM());
	}

	public function getMemberMeronyms(): iterable
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::MEMBER_MERONYM());
	}

	public function getSubstanceMeronyms(): iterable
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::SUBSTANCE_MERONYM());
	}

	public function getPartMeronyms(): iterable
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::PART_MERONYM());
	}

	public function getAttributes(): iterable
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::ATTRIBUTE());
	}

	public function getDerivationallyRelatedForms(): iterable
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::DERIVATIONALLY_RELATED_FORM());
	}

	public function getMemberOfThisDomainTopics(): iterable
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::MEMBER_OF_THIS_DOMAIN_TOPIC());
	}

	public function getMemberOfThisDomainRegions(): iterable
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::MEMBER_OF_THIS_DOMAIN_REGION());
	}

	public function getMemberOfThisDomainUsages(): iterable
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::MEMBER_OF_THIS_DOMAIN_USAGE());
	}
}
