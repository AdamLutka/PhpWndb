<?php
declare(strict_types=1);

namespace AL\PhpWndb\DataStorage;

use AL\PhpWndb\Exceptions\IOException;

interface WordIndexLoaderInterface
{
	/**
	 * @throws IOException
	 */
	public function findLemmaIndexData(string $lemmaToken): ?string;
}

