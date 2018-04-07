<?php
declare(strict_types=1);

namespace AL\PhpWndb\Parsing\ParsedData;

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


	public function getPointerType(): ?string
	{
		return $this->pointerType;
	}

	public function getSynsetOffset(): ?int
	{
		return $this->synsetOffset;
	}

	public function getPartOfSpeech(): ?string
	{
		return $this->partOfSpeech;
	}

	public function getSourceWordIndex(): ?int
	{
		return $this->sourceWordIndex;
	}

	public function getTargetWordIndex(): ?int
	{
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
