<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Words;

use AL\PhpWndb\Model\Relations\RelationsInterface;
use AL\PhpWndb\Model\Relations\RelationPointerTypeEnum;

abstract class WordAbstract implements WordInterface
{
	/** @var int */
	protected $lexId;

	/** @var string */
	protected $lemma;

	/** @var RelationsInterface */
	protected $relations;


	public function __construct(string $lemma, int $lexId, RelationsInterface $relations)
	{
		$this->lemma = $lemma;
		$this->lexId = $lexId;
		$this->relations = $relations;
	}


	public function getLemma(): string
	{
		return $this->lemma;
	}

	public function getLexId(): int
	{
		return $this->lexId;
	}


	public function getAllRelated(): array
	{
		return $this->relations->getAllRelationPointers();
	}

	public function getAntonyms(): array
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::ANTONYM());
	}

	public function getDomainOfSynsetTopics(): array
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::DOMAIN_OF_SYNSET_TOPIC());
	}

	public function getDomainOfSynsetRegions(): array
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::DOMAIN_OF_SYNSET_REGION());
	}

	public function getDomainOfSynsetUsages(): array
	{
		return $this->relations->getRelationPointersOfType(RelationPointerTypeEnum::DOMAIN_OF_SYNSET_USAGE());
	}
}
