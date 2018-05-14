<?php
declare(strict_types=1);

namespace AL\PhpWndb\DataStorage;

use AL\PhpWndb\Exceptions\IOException;
use InvalidArgumentException;

interface FileReaderInterface
{
	/**
	 * @return string[]
	 * @throws IOException
	 */
	public function readAll(): array;

	/**
	 * @throws IOException
	 * @throws InvalidArgumentException
	 */
	public function readBlock(int $blockOffset, int $blockSize): string;

	public function getFileSize(): int;
}
