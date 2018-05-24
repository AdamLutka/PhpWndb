<?php
declare(strict_types=1);

namespace AL\PhpWndb\DataStorage;

use AL\PhpWndb\Exceptions\InvalidStateException;
use AL\PhpWndb\Exceptions\UnknownSynsetOffsetException;

class TimeConsumingSynsetDataLoader implements SynsetDataLoaderInterface
{
	/** @var FileReaderInterface */
	protected $fileReader;

	/** @var int */
	protected $readBlockSize;


	public function __construct(FileReaderInterface $fileReader, int $readBlockSize)
	{
		$this->fileReader = $fileReader;
		$this->readBlockSize = $readBlockSize;
	}


	public function findSynsetData(int $synsetOffset): ?string
	{
		$block = $this->fileReader->readBlock($synsetOffset, $this->readBlockSize);
		list($synsetData, $rest) = explode("\n", $block, 2) + [null, null];

		if ($rest === null) {
			throw new InvalidStateException('There is synset data bigger than ' . $this->readBlockSize . ' B.');
		}

		// synset data is valid only if it starts with synset offset
		return $synsetOffset === (int)$synsetData ? $synsetData : null;
	}

	public function getSynsetData(int $synsetOffset): string
	{
		$synset = $this->findSynsetData($synsetOffset);
		if ($synset === null) {
			throw new UnknownSynsetOffsetException($synsetOffset);
		}

		return $synset;
	}
}
