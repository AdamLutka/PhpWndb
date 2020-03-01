<?php
declare(strict_types=1);

namespace AL\PhpWndb;

use AL\PhpWndb\Model\Synsets\Collections\SynsetCollectionInterface;
use AL\PhpWndb\Model\Synsets\Collections\SynsetCollectionFactoryInterface;
use AL\PhpWndb\Repositories\WordIndexMultiRepositoryInterface;

class WordNet
{
	/** @var SynsetCollectionFactoryInterface */
	protected $synsetCollectionFactory;

	/** @var WordIndexMultiRepositoryInterface */
	protected $wordIndexRepository;


	public function __construct(SynsetCollectionFactoryInterface $synsetCollectionFactory, WordIndexMultiRepositoryInterface $wordIndexRepository)
	{
		$this->synsetCollectionFactory = $synsetCollectionFactory;
		$this->wordIndexRepository = $wordIndexRepository;
	}


	public function searchSynsets(string $lemma): SynsetCollectionInterface
	{
		$wordIndices = $this->wordIndexRepository->findAllWordIndices($lemma);
		$offsets = [
			(string)PartOfSpeechEnum::ADJECTIVE() => [],
			(string)PartOfSpeechEnum::ADVERB() => [],
			(string)PartOfSpeechEnum::NOUN() => [],
			(string)PartOfSpeechEnum::VERB() => [],
		];

		foreach ($wordIndices as $wordIndex) {
			$offsets[(string)$wordIndex->getPartOfSpeech()] = $wordIndex->getSynsetOffsets();
		}

		return $this->synsetCollectionFactory->createSynsetCollection(
			$offsets[(string)PartOfSpeechEnum::ADJECTIVE()],
			$offsets[(string)PartOfSpeechEnum::ADVERB()],
			$offsets[(string)PartOfSpeechEnum::NOUN()],
			$offsets[(string)PartOfSpeechEnum::VERB()]
		);
	}
}
