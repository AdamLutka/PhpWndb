<?php
declare(strict_types=1);

namespace AL\PhpWndb\DataStorage;

class TimeConsumingWordIndexLoader implements WordIndexLoaderInterface
{
	/** @var FileBinarySearcherInterface */
	protected $fileBinarySearcher;


	public function __construct(FileBinarySearcherInterface $fileBinarySearcher)
	{
		$this->fileBinarySearcher = $fileBinarySearcher;
	}


	public function findLemmaIndexData(string $lemmaToken): ?string
	{
		return $this->fileBinarySearcher->searchFor($lemmaToken);
	}
}
