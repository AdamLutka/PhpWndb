<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Indices\Collections;

use AL\PhpWndb\Repositories\WordIndexMultiRepositoryInterface;

class WordIndexCollectionFactory implements WordIndexCollectionFactoryInterface
{
	/** @var WordIndexMultiRepositoryInterface */
	protected $wordIndexRepository;


	public function __construct(WordIndexMultiRepositoryInterface $wordIndexRepository)
	{
		$this->wordIndexRepository = $wordIndexRepository;
	}


	public function createWordIndexCollection(string $lemma): WordIndexCollectionInterface
	{
		return new WordIndexCollection($this->wordIndexRepository, $lemma);
	}
}
