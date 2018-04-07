<?php
declare(strict_types=1);

namespace AL\PhpWndb\DataStorage;

interface SynsetDataLoaderInterface
{
	/**
	 * @throws UnknownSynsetOffsetException
	 */
	public function getSynsetData(int $synsetOffset): string;
}

