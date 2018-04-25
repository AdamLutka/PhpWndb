<?php
declare(strict_types=1);

namespace AL\PhpWndb\DataStorage;

use AL\PhpWndb\Exceptions\UnknownSynsetOffsetException;

interface SynsetDataLoaderInterface
{
	/**
	 * @throws UnknownSynsetOffsetException
	 */
	public function getSynsetData(int $synsetOffset): string;
}

