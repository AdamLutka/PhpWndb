<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Indices\Collections;

use AL\PhpWndb\Model\Indices\WordIndexInterface;
use AL\PhpWndb\PartOfSpeechEnum;
use AL\PhpWndb\Repositories\WordIndexMultiRepositoryInterface;

class WordIndexCollection implements WordIndexCollectionInterface
{
	/** @var WordIndexMultiRepositoryInterface */
	protected $wordIndexRepository;

	/** @var string */
	protected $lemma;

	/** @var (WordIndexInterface|null)[] */
	protected $wordIndices = [];


	public function __construct(WordIndexMultiRepositoryInterface $wordIndexRepository, string $lemma)
	{
		$this->wordIndexRepository = $wordIndexRepository;
		$this->lemma = $lemma;
	}


	public function getAllWordIndices(): array
	{
		return array_filter([
			$this->getNounWordIndex(),
			$this->getVerbWordIndex(),
			$this->getAdjectiveWordIndex(),
			$this->getAdverbWordIndex(),
		]);
	}

	public function getNounWordIndex(): ?WordIndexInterface
	{
		return $this->getWordIndex(PartOfSpeechEnum::NOUN());
	}

	public function getVerbWordIndex(): ?WordIndexInterface
	{
		return $this->getWordIndex(PartOfSpeechEnum::VERB());
	}

	public function getAdjectiveWordIndex(): ?WordIndexInterface
	{
		return $this->getWordIndex(PartOfSpeechEnum::ADJECTIVE());
	}

	public function getAdverbWordIndex(): ?WordIndexInterface
	{
		return $this->getWordIndex(PartOfSpeechEnum::ADVERB());
	}


	protected function getWordIndex(PartOfSpeechEnum $partOfSpeech): ?WordIndexInterface
	{
		$key = (string)$partOfSpeech;
		if (!array_key_exists($key, $this->wordIndices)) {
			$this->wordIndices[$key] = $this->wordIndexRepository->findWordIndexByPartOfSpeech($partOfSpeech, $this->lemma);
		}

		return $this->wordIndices[$key];
	}
}
