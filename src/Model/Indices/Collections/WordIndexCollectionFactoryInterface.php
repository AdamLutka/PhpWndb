<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Indices\Collections;

interface WordIndexCollectionFactoryInterface
{
	public function createWordIndexCollection(string $lemma): WordIndexCollectionInterface;
}
