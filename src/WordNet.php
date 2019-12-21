<?php
declare(strict_types=1);

namespace AL\PhpWndb;

use AL\PhpWndb\Model\Synsets\SynsetInterface;
use AL\PhpWndb\Repositories\SynsetMultiRepositoryInterface;
use AL\PhpWndb\Repositories\WordIndexMultiRepositoryInterface;

class WordNet
{
	/** @var SynsetMultiRepositoryInterface */
	protected $synsetRepository;

	/** @var WordIndexMultiRepositoryInterface */
	protected $wordIndexRepository;


	public function __construct(SynsetMultiRepositoryInterface $synsetRepository, WordIndexMultiRepositoryInterface $wordIndexRepository)
	{
		$this->synsetRepository = $synsetRepository;
		$this->wordIndexRepository = $wordIndexRepository;
	}


	/**
	 * @return SynsetInterface[]
	 */
	public function searchLemma(string $lemma): array
	{
		$synsets = [];
		$wordIndices = $this->wordIndexRepository->findAllWordIndices($lemma);

		foreach ($wordIndices as $wordIndex) {
			$partOfSpeech = $wordIndex->getPartOfSpeech();

			foreach ($wordIndex->getSynsetOffsets() as $synsetOffset) {
				$synsets[] = $this->synsetRepository->getSynsetByPartOfSpeech($partOfSpeech, $synsetOffset);
			}
		}

		return $synsets;
	}
}
