<?php
declare(strict_types=1);

namespace AL\PhpWndb\Repositories;

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

	/** @var SynsetInterface[] */
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

	
	public function getSynset(int $synsetOffset): SynsetInterface
	{
		if (!isset($this->synsets[$synsetOffset])) {
			$synsetData = $this->dataLoader->getSynsetData($synsetOffset);
			$parsedData = $this->parser->parseSynsetData($synsetData);
			$this->synsets[$synsetOffset] = $this->factory->createSynsetFromParseData($parsedData);
		}

		return $this->synsets[$synsetOffset];
	}

	public function dispose(SynsetInterface $synset): void
	{
		unset($this->synsets[$synset->getSynsetOffset()]);
	}
}
