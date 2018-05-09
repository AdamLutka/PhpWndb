<?php
declare(strict_types=1);

namespace AL\PhpWndb;

use AL\PhpWndb\Model\Synsets\SynsetInterface;
use AL\PhpWndb\Repositories\SynsetMultiRepositoryInterface;
use AL\PhpWndb\Repositories\WordIndexRepositoryInterface;

class WordNet
{
	/** @var SynsetMultiRepositoryInterface */
	protected $synsetRepository;

	/** @var WordIndexRepositoryInterface */
	protected $wordIndexRepository;


	public function __construct(SynsetMultiRepositoryInterface $synsetRepository, WordIndexRepositoryInterface $wordIndexRepository)
	{
		$this->synsetRepository = $synsetRepository;
		$this->wordIndexRepository = $wordIndexRepository;
	}


	/**
	 * @return SynsetInterface[]
	 */
	public function searchLemma(string $lemma): iterable
	{
		$wordIndex = $this->wordIndexRepository->findWordIndex($lemma);
		if ($wordIndex === null) {
			return [];  // unknown lemma isn't inside any synset
		}

		$partOfSpeech = $wordIndex->getPartOfSpeech();

		return array_map(function (int $synsetOffset) use ($partOfSpeech) {
			return $this->synsetRepository->getSynsetByPartOfSpeech($partOfSpeech, $synsetOffset);
		}, $wordIndex->getSynsetOffsets());
	}
}
