<?php
declare(strict_types=1);

namespace AL\PhpWndb\Parsing\ParsedData;

class ParsedWordIndex implements ParsedWordIndexInterface
{
	/** @var string|null */
	protected $lemma;

	/** @var string|null */
	protected $partOfSpeech;

	/** @var string[] */
	protected $pointerTypes = [];

	/** @var int[] */
	protected $synsetOffsets = [];


	public function getLemma(): ?string
	{
		return $this->lemma;
	}

	public function getPartOfSpeech(): ?string
	{
		return $this->partOfSpeech;
	}

	public function getPointerTypes(): iterable
	{
		return $this->pointerTypes;
	}

	public function getSynsetOffsets(): iterable
	{
		return $this->synsetOffsets;
	}


	public function setLemma(string $lemma): void
	{
		$this->lemma = $lemma;
	}

	public function setPartOfSpeech(string $partOfSpeech): void
	{
		$this->partOfSpeech = $partOfSpeech;
	}

	public function addPointerType(string $pointerType): void
	{
		$this->pointerTypes[] = $pointerType;
	}

	public function addSynsetOffset(int $synsetOffset): void
	{
		$this->synsetOffsets[] = $synsetOffset;
	}
}
