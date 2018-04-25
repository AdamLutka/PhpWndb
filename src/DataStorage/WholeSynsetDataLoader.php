<?php
declare(strict_types = 1);

namespace AL\PhpWndb\DataStorage;

use AL\PhpWndb\Exceptions\InvalidStateException;
use AL\PhpWndb\Exceptions\IOException;
use AL\PhpWndb\Exceptions\UnknownSynsetOffsetException;

class WholeSynsetDataLoader implements SynsetDataLoaderInterface
{
	/** @private */
	const SYNSET_OFFSET_CHARS_COUNT = 8;


	/** @var string */
	private $dataFilepath;

	/** @var string[] */
	private $synsetData;


	public function __construct(string $dataFilepath)
	{
		$this->dataFilepath = $dataFilepath;
	}


	public function getSynsetData(int $synsetOffset): string
	{
		if ($this->synsetData === null) {
			$this->synsetData = $this->loadSynsetData();
		}

		if (isset($this->synsetData[$synsetOffset])) {
			return $this->synsetData[$synsetOffset];
		}
		else {
			throw new UnknownSynsetOffsetException($synsetOffset);
		}
	}


	/**
	 * @return string[] SYNSET_OFFSET => SYNSET_DATA
	 * @throws IOException
	 */
	private function loadSynsetData(): array
	{
		if (!is_readable($this->dataFilepath)) {
			throw new IOException('File is not readable: ' . $this->dataFilepath);
		}

		$lines = file($this->dataFilepath, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
		if ($lines === false) {
			throw new IOException('File read failed: ' . $this->dataFilepath);
		}

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
