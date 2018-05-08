<?php
declare(strict_types=1);

namespace AL\PhpWndb\DataStorage;

use AL\PhpWndb\Exceptions\UnknownSynsetOffsetException;

interface SynsetDataLoaderInterface
{
	public function findSynsetData(int $synsetOffset): ?string;

	/**
	 * @throws UnknownSynsetOffsetException
	 */
	public function getSynsetData(int $synsetOffset): string;
}

