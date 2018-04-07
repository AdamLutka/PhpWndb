<?php
declare(strict_types=1);

namespace AL\PhpWndb\Parsing\ParsedData;

class ParsedFrameData implements ParsedFrameDataInterface
{
	/** @var int|null */
	protected $frameNumber;

	/** @var int|null */
	protected $wordIndex;



	public function getFrameNumber(): ?int
	{
		return $this->frameNumber;
	}

	public function getWordIndex(): ?int
	{
		return $this->wordIndex;
	}


	public function setFrameNumber(int $frameNumber): void
	{
		$this->frameNumber = $frameNumber;
	}

	public function setWordIndex(int $wordIndex): void
	{
		$this->wordIndex = $wordIndex;
	}
}
