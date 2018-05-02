<?php
declare(strict_types=1);

namespace AL\PhpWndb\DataStorage;

use AL\PhpWndb\Exceptions\IOException;

class FileReader implements FileReaderInterface
{
	/** @var string */
	protected $filepath;


	public function __construct(string $filepath)
	{
		$this->filepath = $filepath;
	}


	public function readAll(): array
	{
		if (!is_readable($this->filepath)) {
			throw new IOException("File ({$this->filepath}) is not readable.");
		}

		$lines = @file($this->filepath, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
		if ($lines === false) {
			throw new IOException("File ({$this->filepath}) read failed: " . error_get_last()['message']);
		}

		return $lines;
	}
}
