<?php
declare(strict_types=1);

namespace AL\PhpWndb\Parsing\ParsedData;

use AL\PhpWndb\Exceptions\InvalidStateException;

class ParsedPointerData implements ParsedPointerDataInterface
{
	/** @var string|null */
	protected $pointerType;

	/** @var int|null */
	protected $synsetOffset;

	/** @var string|null */
	protected $partOfSpeech;

	/** @var int|null */
	protected $sourceWordIndex;

	/** @var int|null */
	protected $targetWordIndex;


	public function getPointerType(): string
	{
		if ($this->pointerType === null) {
			throw new InvalidStateException('Pointer type is not set.');
		}

		return $this->pointerType;
	}

	public function getSynsetOffset(): int
	{
		if ($this->synsetOffset === null) {
			throw new InvalidStateException('Synset offset is not set.');
		}

		return $this->synsetOffset;
	}

	public function getPartOfSpeech(): string
	{
		if ($this->partOfSpeech === null) {
			throw new InvalidStateException('Part of speech is not set.');
		}

		return $this->partOfSpeech;
	}

	public function getSourceWordIndex(): int
	{
		if ($this->sourceWordIndex === null) {
			throw new InvalidStateException('Source word index is not set.');
		}

		return $this->sourceWordIndex;
	}

	public function getTargetWordIndex(): int
	{
		if ($this->targetWordIndex === null) {
			throw new InvalidStateException('Target word index is not set.');
		}

		return $this->targetWordIndex;
	}


	public function setPointerType(string $pointerType): void
	{
		$this->pointerType = $pointerType;
	}

	public function setSynsetOffset(int $synsetOffset): void
	{
		$this->synsetOffset = $synsetOffset;
	}

	public function setPartOfSpeech(string $partOfSpeech): void
	{
		$this->partOfSpeech = $partOfSpeech;
	}

	public function setSourceWordIndex(int $sourceWordIndex): void
	{
		$this->sourceWordIndex = $sourceWordIndex;
	}

	public function setTargetWordIndex(int $targetWordIndex): void
	{
		$this->targetWordIndex = $targetWordIndex;
	}
}
