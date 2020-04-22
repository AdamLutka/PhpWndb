<?php
declare(strict_types=1);

namespace AL\PhpWndb;

use AL\PhpWndb\Model\Indices\Collections\WordIndexCollectionFactoryInterface;
use AL\PhpWndb\Model\Indices\Collections\WordIndexCollectionInterface;
use AL\PhpWndb\Model\Synsets\Collections\SynsetCollectionFactoryInterface;
use AL\PhpWndb\Model\Synsets\Collections\SynsetCollectionInterface;

class WordNet
{
	/** @var SynsetCollectionFactoryInterface */
	protected $synsetCollectionFactory;

	/** @var WordIndexCollectionFactoryInterface */
	protected $wordIndexCollectionFactory;


	public function __construct(SynsetCollectionFactoryInterface $synsetCollectionFactory, WordIndexCollectionFactoryInterface $wordIndexCollectionFactory)
	{
		$this->synsetCollectionFactory = $synsetCollectionFactory;
		$this->wordIndexCollectionFactory = $wordIndexCollectionFactory;
	}


	public function searchSynsets(string $lemma): SynsetCollectionInterface
	{
		return $this->synsetCollectionFactory->createSynsetCollection(
			$this->searchWordIndices($lemma)
		);
	}

	public function searchWordIndices(string $lemma): WordIndexCollectionInterface
	{
		return $this->wordIndexCollectionFactory->createWordIndexCollection($lemma);
	}
}
