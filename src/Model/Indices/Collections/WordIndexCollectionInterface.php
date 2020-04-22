<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Indices\Collections;

use AL\PhpWndb\Model\Indices\WordIndexInterface;

interface WordIndexCollectionInterface
{
	/**
	 * @return WordIndexInterface[]
	 */
	public function getAllWordIndices(): array;

	public function getNounWordIndex(): ?WordIndexInterface;

	public function getVerbWordIndex(): ?WordIndexInterface;

	public function getAdjectiveWordIndex(): ?WordIndexInterface;

	public function getAdverbWordIndex(): ?WordIndexInterface;
}
