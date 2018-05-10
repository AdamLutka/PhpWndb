<?php
declare(strict_types=1);

namespace AL\PhpWndb\Parsing\ParsedData;

use AL\PhpWndb\Exceptions\InvalidStateException;

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


	public function getLemma(): string
	{
		if ($this->lemma === null) {
			throw new InvalidStateException('Lemma is not set.');
		}

		return $this->lemma;
	}

	public function getPartOfSpeech(): string
	{
		if ($this->partOfSpeech === null) {
			throw new InvalidStateException('Part of speech is not set.');
		}

		return $this->partOfSpeech;
	}

	public function getPointerTypes(): array
	{
		return $this->pointerTypes;
	}

	public function getSynsetOffsets(): array
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
