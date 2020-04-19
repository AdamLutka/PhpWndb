<?php
declare(strict_types=1);

namespace AL\PhpWndb\Repositories;

use AL\PhpWndb\DataMapping\LemmaMapperInterface;
use AL\PhpWndb\DataStorage\WordIndexLoaderInterface;
use AL\PhpWndb\Model\Indices\WordIndexFactoryInterface;
use AL\PhpWndb\Model\Indices\WordIndexInterface;
use AL\PhpWndb\Parsing\WordIndexParserInterface;

class WordIndexRepository implements WordIndexRepositoryInterface
{
	/** @var LemmaMapperInterface */
	protected $lemmaMapper;

	/** @var WordIndexLoaderInterface */
	protected $wordIndexLoader;

	/** @var WordIndexParserInterface */
	protected $wordIndexParser;

	/** @var WordIndexFactoryInterface */
	protected $wordIndexFactory;

	/** @var (WordIndexInterface|null)[] */
	protected $wordIndices = [];


	public function __construct(
		LemmaMapperInterface $lemmaMapper,
		WordIndexLoaderInterface $wordIndexLoader,
		WordIndexParserInterface $wordIndexParser,
		WordIndexFactoryInterface $wordIndexFactory
	) {
		$this->wordIndexLoader = $wordIndexLoader;
		$this->wordIndexParser = $wordIndexParser;
		$this->wordIndexFactory = $wordIndexFactory;
		$this->lemmaMapper = $lemmaMapper;
	}


	public function findWordIndex(string $lemma): ?WordIndexInterface
	{
		if (empty($lemma)) {
			return null;
		}

		$lemmaToken = $this->getLemmaToken($lemma);
		if (!array_key_exists($lemmaToken, $this->wordIndices)) {
			$this->wordIndices[$lemmaToken] = $this->doFindWordIndex($lemmaToken);
		}

		return $this->wordIndices[$lemmaToken];
	}

	public function dispose(WordIndexInterface $wordIndex): void
	{
		$lemmaToken = $this->getLemmaToken($wordIndex->getLemma());
		unset($this->wordIndices[$lemmaToken]);
	}


	protected function getLemmaToken(string $lemma): string
	{
		return $this->lemmaMapper->lemmaToToken($lemma);
	}

	protected function doFindWordIndex(string $lemmaToken): ?WordIndexInterface
	{
		$wordIndexData = $this->wordIndexLoader->findLemmaIndexData($lemmaToken);
		if ($wordIndexData === null) {
			return null;
		}

		$parsedWordIndex = $this->wordIndexParser->parseWordIndex($wordIndexData);
		return $this->wordIndexFactory->createWordIndexFromParsedData($parsedWordIndex);
	}
}
