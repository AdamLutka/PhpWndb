<?php
declare(strict_types=1);

namespace AL\PhpWndb\Parsing\ParsedData;

use AL\PhpWndb\Exceptions\InvalidStateException;

class ParsedSynsetData implements ParsedSynsetDataInterface
{
	/** @var int|null */
	protected $synsetOffset;

	/** @var int|null */
	protected $lexFileNumber;

	/** @var string|null */
	protected $partOfSpeech;

	/** @var string|null */
	protected $gloss;

	/** @var ParsedWordDataInterface[] */
	protected $words = [];

	/** @var ParsedPointerDataInterface[] */
	protected $pointers = [];

	/** @var ParsedFrameDataInterface[] */
	protected $frames = [];


	public function getSynsetOffset(): int
	{
		if ($this->synsetOffset === null) {
			throw new InvalidStateException('Synset offset is not set.');
		}

		return $this->synsetOffset;
	}

	public function getLexFileNumber(): int
	{
		if ($this->lexFileNumber === null) {
			throw new InvalidStateException('Lex file number is not set.');
		}

		return $this->lexFileNumber;
	}

	public function getPartOfSpeech(): string
	{
		if ($this->partOfSpeech === null) {
			throw new InvalidStateException('Part of speech is not set.');
		}

		return $this->partOfSpeech;
	}

	public function getGloss(): string
	{
		if ($this->gloss === null) {
			throw new InvalidStateException('Gloss is not set.');
		}

		return $this->gloss;
	}

	public function getWords(): array
	{
		return $this->words;
	}

	public function getPointers(): array
	{
		return $this->pointers;
	}

	public function getFrames(): array
	{
		return $this->frames;
	}


	public function setSynsetOffset(int $synsetOffset): void
	{
		$this->synsetOffset = $synsetOffset;
	}

	public function setLexFileNumber(int $lexFileNumber): void
	{
		$this->lexFileNumber = $lexFileNumber;
	}

	public function setPartOfSpeech(string $partOfSpeech): void
	{
		$this->partOfSpeech = $partOfSpeech;
	}

	public function setGloss(string $gloss): void
	{
		$this->gloss = $gloss;
	}

	public function addWord(ParsedWordDataInterface $word): void
	{
		$this->words[] = $word;
	}

	public function addPointer(ParsedPointerDataInterface $pointer): void
	{
		$this->pointers[] = $pointer;
	}

	public function addFrame(ParsedFrameDataInterface $frame): void
	{
		$this->frames[] = $frame;
	}
}
