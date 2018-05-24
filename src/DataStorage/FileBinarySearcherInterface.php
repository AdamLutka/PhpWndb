<?php
declare(strict_types=1);

namespace AL\PhpWndb\DataStorage;

use AL\PhpWndb\Exceptions\InvalidStateException;
use InvalidArgumentException;

interface FileBinarySearcherInterface
{
	/**
	 * @throws InvalidStateException
	 * @throws InvalidArgumentException
	 */
	public function searchFor(string $recordPrefix): ?string;
}
