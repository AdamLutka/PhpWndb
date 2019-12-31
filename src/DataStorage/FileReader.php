<?php
declare(strict_types=1);

namespace AL\PhpWndb\DataStorage;

use AL\PhpWndb\Exceptions\IOException;
use InvalidArgumentException;

class FileReader implements FileReaderInterface
{
	/** @var string */
	protected $filepath;

	/** @var resource|null */
	protected $handle;

	/** @var int|null */
	protected $filesize;


	public function __construct(string $filepath)
	{
		$this->filepath = $filepath;
	}

	public function __destruct()
	{
		if ($this->handle !== null) {
			@fclose($this->handle);
		}
	}


	public function readAll(): array
	{
		$this->checkReadability();

		$lines = @file($this->filepath, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
		if ($lines === false) {
			$lastError = error_get_last();
			throw new IOException("File ({$this->filepath}) read failed: " . ($lastError ? $lastError['message'] : 'unknown error'));
		}

		return $lines;
	}


	public function readBlock(int $blockOffset, int $blockSize): string
	{
		if ($blockOffset < 0) {
			throw new InvalidArgumentException('Block offset has to be nonnegative integer.');
		}

		if ($blockSize <= 0) {
			throw new InvalidArgumentException('Block size has to be positive integer.');
		}

		if ($this->handle === null) {
			$this->openFile();
		}

		$this->seekFile($blockOffset);
		return $this->readFile($blockSize);
	}

	public function getFileSize(): int
	{
		if ($this->filesize === null) {
			$this->checkReadability();
			$this->filesize = $this->findOutFileSize();
		}

		return $this->filesize;
	}


	/**
	 * @throws IOException
	 */
	protected function checkReadability(): void
	{
		if (!is_readable($this->filepath)) {
			throw new IOException("File ({$this->filepath}) is not readable.");
		}
	}

	/**
	 * @throws IOException
	 */
	protected function openFile(): void
	{
		$this->checkReadability();

		$handle = @fopen($this->filepath, 'r');
		if ($handle === false) {
			throw new IOException("File ({$this->filepath}) open failed: " . $this->getLastErrorMessage());
		}

		$this->handle = $handle;
	}

	/**
	 * @throws IOException
	 */
	protected function seekFile(int $offset): void
	{
		assert(is_resource($this->handle));

		$seeked = @fseek($this->handle, $offset);
		if ($seeked < 0) {
			throw new IOException("File ({$this->filepath}) seek failed: " . $this->getLastErrorMessage());
		}
	}

	/**
	 * @throws IOException
	 */
	protected function readFile(int $size): string
	{
		assert(is_resource($this->handle));

		$result = @fread($this->handle, $size);
		if ($result === false) {
			throw new IOException("File ({$this->filepath}) read failed: " . $this->getLastErrorMessage());
		}

		return $result;
	}

	/**
	 * @throws IOException
	 */
	protected function findOutFileSize(): int
	{
		$size = @filesize($this->filepath);
		if ($size === false) {
			throw new IOException("File ({$this->filepath}) get size failed: " . $this->getLastErrorMessage());
		}

		return $size;
	}

	/**
	 * @throws IOException
	 */
	protected function getLastErrorMessage(): string
	{
		return error_get_last()['message'] ?? '???';
	}
}
