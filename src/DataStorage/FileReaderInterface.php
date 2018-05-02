<?php
declare(strict_types=1);

namespace AL\PhpWndb\DataStorage;

use AL\PhpWndb\Exceptions\IOException;

interface FileReaderInterface
{
	/**
	 * @return string[]
	 * @throws IOException
	 */
	public function readAll(): array;
}
