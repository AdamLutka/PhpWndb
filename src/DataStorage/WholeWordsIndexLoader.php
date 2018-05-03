<?php
declare(strict_types=1);

namespace AL\PhpWndb\DataStorage;

use AL\PhpWndb\DataMapping\LemmaMapperInterface;
use AL\PhpWndb\Exceptions\IOException;
use AL\PhpWndb\Exceptions\InvalidStateException;

class WholeWordsIndexLoader implements WordsIndexLoaderInterface
{
	/** @var FileReaderInterface */
	protected $reader;

	/** @var LemmaMapperInterface */
	protected $lemmaMapper;

	/** @var string[] */
	protected $indexData;


	public function __construct(FileReaderInterface $reader, LemmaMapperInterface $lemmaMapper)
	{
		$this->reader = $reader;
		$this->lemmaMapper = $lemmaMapper;
	}


	public function findLemmaIndexData(string $lemma): ?string
	{
		if ($this->indexData === null) {
			$this->indexData = $this->loadIndexData();
		}

		$token = $this->lemmaMapper->lemmaToToken($lemma);
		return $this->indexData[$token] ?? null;
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
