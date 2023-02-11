<?php

declare(strict_types=1);

namespace AL\PhpWndb\Storage;

use AL\PhpWndb\Storage\Exception\FileSystemException;
use InvalidArgumentException;

class FileStream implements Stream
{
    /**
     * @param resource $resource
     */
    final private function __construct(
        protected readonly string $filePath,
        private $resource,
    ) {
    }

    /**
     * @throws FileSystemException
     */
    public function __destruct()
    {
        if (! \fclose($this->resource)) {
            throw static::createException("File `{$this->filePath}` close failed");
        }
    }

    /**
     * @throws FileSystemException
     */
    public function seek(int $offset): void
    {
        if (@\fseek($this->resource, $offset) < 0) {
            throw static::createException("File `{$this->filePath}` seek failed");
        }
    }

    /**
     * @throws FileSystemException
     */
    public function tell(): int
    {
        $position = @\ftell($this->resource);
        return $position === false
            ? throw static::createException("File `{$this->filePath}` tell failed")
            : $position;
    }

    /**
     * @throws FileSystemException
     */
    public function read(int $length): string
    {
        if ($length < 0) {
            throw new InvalidArgumentException('`length` has to be non-negative integer.');
        }

        $data = @\fread($this->resource, $length);
        return $data === false
            ? throw static::createException("File `{$this->filePath}` read failed")
            : $data;
    }

    /**
     * @throws FileSystemException
     */
    public function getLength(): int
    {
        $size = \filesize($this->filePath);

        return $size === false
            ? throw static::createException("File `{$this->filePath}` size failed")
            : $size;
    }

    /**
     * @throws FileSystemException
     */
    public static function open(string $filePath): static
    {
        $resource = @\fopen($filePath, 'r');

        return $resource === false
            ? throw static::createException("Open `$filePath` failed")
            : new static($filePath, $resource);
    }

    protected static function createException(string $message): FileSystemException
    {
        $errorMessage = \error_get_last()['message'] ?? 'unknown error';
        return new FileSystemException("{$message}: {$errorMessage}");
    }
}
