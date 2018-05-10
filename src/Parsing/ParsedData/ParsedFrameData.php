<?php
declare(strict_types=1);

namespace AL\PhpWndb\Parsing\ParsedData;

use AL\PhpWndb\Exceptions\InvalidStateException;

class ParsedFrameData implements ParsedFrameDataInterface
{
	/** @var int|null */
	protected $frameNumber;

	/** @var int|null */
	protected $wordIndex;



	public function getFrameNumber(): int
	{
		if ($this->frameNumber === null) {
			throw new InvalidStateException('Frame number is not set.');
		}

		return $this->frameNumber;
	}

	public function getWordIndex(): int
	{
		if ($this->wordIndex === null) {
			throw new InvalidStateException('Word index is not set.');
		}

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
