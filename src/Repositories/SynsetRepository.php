<?php
declare(strict_types=1);

namespace AL\PhpWndb\Repositories;

use AL\PhpWndb\Exceptions\UnknownSynsetOffsetException;
use AL\PhpWndb\DataStorage\SynsetDataLoaderInterface;
use AL\PhpWndb\Model\Synsets\SynsetFactoryInterface;
use AL\PhpWndb\Model\Synsets\SynsetInterface;
use AL\PhpWndb\Parsing\SynsetDataParserInterface;

class SynsetRepository implements SynsetRepositoryInterface
{
	/** @var SynsetFactoryInterface */
	protected $factory;

	/** @var SynsetDataParserInterface */
	protected $parser;

	/** @var SynsetDataLoaderInterface */
	protected $dataLoader;

	/** @var (SynsetInterface|null)[] */
	protected $synsets = [];


	public function __construct(
		SynsetDataLoaderInterface $dataLoader,
		SynsetDataParserInterface $parser,
		SynsetFactoryInterface $factory
	) {
		$this->dataLoader = $dataLoader;
		$this->parser = $parser;
		$this->factory = $factory;
	}


	public function findSynset(int $synsetOffset): ?SynsetInterface
	{
		if (!array_key_exists($synsetOffset, $this->synsets)) {
			$this->synsets[$synsetOffset] = $this->doFindSynset($synsetOffset);
		}

		return $this->synsets[$synsetOffset] ?? null;
	}
	
	public function getSynset(int $synsetOffset): SynsetInterface
	{
		$synset = $this->findSynset($synsetOffset);
		if ($synset === null) {
			throw new UnknownSynsetOffsetException($synsetOffset);
		}

		return $synset;
	}

	public function dispose(SynsetInterface $synset): void
	{
		unset($this->synsets[$synset->getSynsetOffset()]);
	}


	protected function doFindSynset(int $synsetOffset): ?SynsetInterface
	{
		$synsetData = $this->dataLoader->findSynsetData($synsetOffset);
		if ($synsetData === null) {
			return null;
		}

		$parsedData = $this->parser->parseSynsetData($synsetData);
		return $this->factory->createSynsetFromParsedData($parsedData);
	}
}
