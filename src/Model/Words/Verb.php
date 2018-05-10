<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Words;

use AL\PhpWndb\Model\Relations\RelationPointerTypeEnum;
use AL\PhpWndb\Model\Relations\RelationsInterface;

class Verb extends WordAbstract implements VerbInterface
{
	/** @var int[] */
	protected $frames;


	/**
	 * @param int[] $frames
	 */
	public function __construct(string $lemma, int $lexId, RelationsInterface $relations, array $frames)
	{
		parent::__construct($lemma, $lexId, $relations);
		$this->frames = $frames;
	}


	public function getHypernyms(): array
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::HYPERNYM());
	}

	public function getHyponyms(): array
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::HYPONYM());
	}

	public function getEntailments(): array
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::ENTAILMENT());
	}

	public function getCauses(): array
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::CAUSE());
	}

	public function getAlsoSee(): array
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::ALSO_SEE());
	}

	public function getVerbGroups(): array
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::VERB_GROUP());
	}

	public function getDerivationallyRelatedForms(): array
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::DERIVATIONALLY_RELATED_FORM());
	}


	public function getFrames(): array
	{
		return $this->frames;
	}
}
