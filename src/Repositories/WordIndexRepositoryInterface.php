<?php
declare(strict_types=1);

namespace AL\PhpWndb\Repositories;

use AL\PhpWndb\Model\Indices\WordIndexInterface;

interface WordIndexRepositoryInterface
{
	public function findWordIndex(string $lemma): ?WordIndexInterface;

	public function dispose(WordIndexInterface $wordIndex): void;
}
