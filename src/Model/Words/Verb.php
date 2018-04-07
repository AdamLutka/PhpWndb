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
	public function __construct(string $lemma, int $lexId, RelationsInterface $relations, iterable $frames)
	{
		parent::__construct($lemma, $lexId, $relations);
		$this->frames = $frames;
	}


	public function getHypernyms(): iterable
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::HYPERNYM());
	}

	public function getHyponyms(): iterable
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::HYPONYM());
	}

	public function getEntailments(): iterable
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::ENTAILMENT());
	}

	public function getCauses(): iterable
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::CAUSE());
	}

	public function getAlsoSee(): iterable
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::ALSO_SEE());
	}

	public function getVerbGroups(): iterable
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::VERB_GROUPS());
	}

	public function getDerivationallyRelatedForms(): iterable
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::DERIVATIONALLY_RELATED_FORM());
	}


	public function getFrames(): iterable
	{
		return $this->frames;
	}
}
