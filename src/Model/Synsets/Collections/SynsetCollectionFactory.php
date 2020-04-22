<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Synsets\Collections;

use AL\PhpWndb\Model\Indices\Collections\WordIndexCollectionInterface;
use AL\PhpWndb\Repositories\SynsetMultiRepositoryInterface;

class SynsetCollectionFactory implements SynsetCollectionFactoryInterface
{
	/** @var SynsetMultiRepositoryInterface */
	protected $synsetMultiRepository;


	public function __construct(SynsetMultiRepositoryInterface $synsetMultiRepository)
	{
		$this->synsetMultiRepository = $synsetMultiRepository;
	}


	public function createSynsetCollection(WordIndexCollectionInterface $wordIndexCollection): SynsetCollectionInterface {
		return new SynsetCollection($this->synsetMultiRepository, $wordIndexCollection);
	}
}
