<?php
declare(strict_types=1);

namespace AL\PhpWndb\DataStorage;

use AL\PhpWndb\Exceptions\InvalidStateException;
use AL\PhpWndb\Exceptions\UnknownSynsetOffsetException;

interface SynsetDataLoaderInterface
{
	/**
	 * @throws InvalidStateException
	 */
	public function findSynsetData(int $synsetOffset): ?string;

	/**
	 * @throws UnknownSynsetOffsetException
	 * @throws InvalidStateException
	 */
	public function getSynsetData(int $synsetOffset): string;
}

