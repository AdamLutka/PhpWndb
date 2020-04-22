<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Synsets\Collections;

use AL\PhpWndb\Model\Indices\Collections\WordIndexCollectionInterface;

interface SynsetCollectionFactoryInterface
{
	public function createSynsetCollection(WordIndexCollectionInterface $wordIndexCollection): SynsetCollectionInterface;
}
