<?php
declare(strict_types = 1);

namespace AL\PhpWndb\DataStorage;

use AL\PhpWndb\Exceptions\InvalidStateException;
use AL\PhpWndb\Exceptions\IOException;
use AL\PhpWndb\Exceptions\UnknownSynsetOffsetException;

class MemoryConsumingSynsetDataLoader implements SynsetDataLoaderInterface
{
	/** @private */
	const SYNSET_OFFSET_CHARS_COUNT = 8;


	/** @var FileReaderInterface */
	private $reader;

	/** @var string[] */
	private $synsetData;


	public function __construct(FileReaderInterface $reader)
	{
		$this->reader = $reader;
	}


	public function findSynsetData(int $synsetOffset): ?string
	{
		if ($this->synsetData === null) {
			$this->synsetData = $this->loadSynsetData();
		}

		return $this->synsetData[$synsetOffset] ?? null;
	}

	public function getSynsetData(int $synsetOffset): string
	{
		$synset = $this->findSynsetData($synsetOffset);
		if ($synset === null) {
			throw new UnknownSynsetOffsetException($synsetOffset);
		}

		return $synset;
	}


	/**
	 * @return string[] SYNSET_OFFSET => SYNSET_DATA
	 * @throws IOException
	 */
	private function loadSynsetData(): array
	{
		$lines = $this->reader->readAll();
		return $this->transformSynsetData($lines);
	}

	/**
	 * @param string[] $rawData
	 * @return string[]
	 */
	private function transformSynsetData(array $rawData): array
	{
		$synsetData = [];

		foreach ($rawData as $rawDataItem) {
			if (!$this->isValidSynsetRawDataItem($rawDataItem)) {
				continue;
			}

			$synsetOffset = (int)substr($rawDataItem, 0, self::SYNSET_OFFSET_CHARS_COUNT);
			if (isset($synsetData[$synsetOffset])) {
				throw new InvalidStateException("Synset offset $synsetOffset is not unique in the dataset.");
			}
			$synsetData[$synsetOffset] = $rawDataItem;
		}

		return $synsetData;
	}

	private function isValidSynsetRawDataItem(string $rawDataItem): bool
	{
		return !empty($rawDataItem) && $rawDataItem[0] >= '0' && $rawDataItem[0] <= '9';
	}
}
