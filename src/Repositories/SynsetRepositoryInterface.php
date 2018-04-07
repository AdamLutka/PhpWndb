<?php
declare(strict_types=1);

namespace AL\PhpWndb\Repositories;

use AL\PhpWndb\Model\Synsets\SynsetInterface;

interface SynsetRepositoryInterface
{
	public function getSynset(int $synsetOffset): SynsetInterface;

	public function dispose(SynsetInterface $synset): void;
}
