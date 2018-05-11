<?php
declare(strict_types=1);

namespace AL\PhpWndb\DataStorage;

use AL\PhpWndb\Exceptions\InvalidStateException;
use AL\PhpWndb\Exceptions\UnknownSynsetOffsetException;

class TimeConsumingSynsetDataLoader implements SynsetDataLoaderInterface
{
	/** @internal */
	const BLOCK_SIZE = 16 * 1024; // 16 KiB


	/** @var FileReaderInterface */
	protected $fileReader;


	public function __construct(FileReaderInterface $fileReader)
	{
		$this->fileReader = $fileReader;
	}


	public function findSynsetData(int $synsetOffset): ?string
	{
		$block = $this->fileReader->readBlock($synsetOffset, static::BLOCK_SIZE);
		list($synsetData, $rest) = explode("\n", $block, 2) + [null, null];

		if ($rest === null) {
			throw new InvalidStateException('There is synset data bigger than ' . static::BLOCK_SIZE . ' B.');
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
