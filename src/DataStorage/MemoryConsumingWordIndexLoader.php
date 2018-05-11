<?php
declare(strict_types=1);

namespace AL\PhpWndb\DataStorage;

use AL\PhpWndb\Exceptions\IOException;
use AL\PhpWndb\Exceptions\InvalidStateException;

class MemoryConsumingWordIndexLoader implements WordIndexLoaderInterface
{
	/** @var FileReaderInterface */
	protected $reader;

	/** @var string[] */
	protected $indexData;


	public function __construct(FileReaderInterface $reader)
	{
		$this->reader = $reader;
	}


	public function findLemmaIndexData(string $lemmaToken): ?string
	{
		if ($this->indexData === null) {
			$this->indexData = $this->loadIndexData();
		}

		return $this->indexData[$lemmaToken] ?? null;
	}


	/**
	 * @return string[] LEMMA => INDEX_DATA
	 * @throws IOException
	 */
	protected function loadIndexData(): array
	{
		$lines = $this->reader->readAll();
		return $this->transformIndexData($lines);
	}

	/**
	 * @param string[] $rawData
	 * @return string[]
	 */
	protected function transformIndexData(array $rawData): array
	{
		$indexData = [];

		foreach ($rawData as $rawDataItem) {
			if (!$this->isValidIndexRawDataItem($rawDataItem)) {
				continue;
			}

			list($lemmaToken) = explode(' ', $rawDataItem, 2);
			if (isset($indexData[$lemmaToken])) {
				throw new InvalidStateException("Lemma '$lemmaToken' is not unique in the index.");
			}
			$indexData[$lemmaToken] = $rawDataItem;
		}

		return $indexData;
	}

	protected function isValidIndexRawDataItem(string $rawDataItem): bool
	{
		return !empty($rawDataItem) && $rawDataItem[0] !== ' ';
	}
}
