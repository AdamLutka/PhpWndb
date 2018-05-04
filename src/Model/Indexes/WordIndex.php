<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Indexes;

use AL\PhpWndb\Model\Relations\RelationPointerTypeEnum;
use AL\PhpWndb\PartOfSpeechEnum;

class WordIndex implements WordIndexInterface
{
	/** @var string */
	protected $lemma;

	/** @var PartOfSpeechEnum */
	protected $partOfSpeech;

	/** @var RelationPointerTypeEnum[] */
	protected $relationPointerTypes;

	/** @var int[] */
	protected $synsetOffsets;


	/**
	 * @param RelationPointerTypeEnum[] $relationPointerTypes
	 * @param int[] $synsetOffsets
	 */
	public function __construct(string $lemma, PartOfSpeechEnum $partOfSpeech, iterable $relationPointerTypes, iterable $synsetOffsets)
	{
		$this->lemma = $lemma;
		$this->partOfSpeech = $partOfSpeech;
		$this->relationPointerTypes = $relationPointerTypes;
		$this->synsetOffsets = $synsetOffsets;
	}


	public function getLemma(): string
	{
		return $this->lemma;
	}

	public function getPartOfSpeech(): PartOfSpeechEnum
	{
		return $this->partOfSpeech;
	}

	public function getRelationPointerTypes(): iterable
	{
		return $this->relationPointerTypes;
	}

	public function getSynsetOffsets(): iterable
	{
		return $this->synsetOffsets;
	}
}
