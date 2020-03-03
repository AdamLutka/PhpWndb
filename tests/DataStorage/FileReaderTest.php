<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests\DataStorage;

use AL\PhpWndb\Exceptions\IOException;
use AL\PhpWndb\DataStorage\FileReader;
use AL\PhpWndb\Tests\BaseTestAbstract;
use InvalidArgumentException;

class FileReaderTest extends BaseTestAbstract
{
	public function testReadAll(): void
	{
		$reader = $this->createReader();

		static::assertSame([
			'a123',
			'b456',
			'c78',
			'd9',
		], $reader->readAll());
	}

	public function testReadAllNotExist(): void
	{
		$this->expectException(IOException::class);

		$reader = new FileReader(__DIR__ . '/FileReaderTest.not_exist');
		$reader->readAll();
	}


	public function testReadBlock(): void
	{
		$reader = $this->createReader();

		static::assertSame("3\n\nb4", $reader->readBlock(3, 5));
		static::assertSame('a12', $reader->readBlock(0, 3));
	}

	public function testReadBlockNotExist(): void
	{
		$this->expectException(IOException::class);

		$reader = new FileReader(__DIR__ . '/FileReaderTest.not_exist');
		$reader->readAll();
	}

	public function testReadBlockInvalidOffset(): void
	{
		$this->expectException(InvalidArgumentException::class);

		$reader = $this->createReader();
		$reader->readBlock(-1, 10);
	}

	public function testReadBlockInvalidSize(): void
	{
		$this->expectException(InvalidArgumentException::class);

		$reader = $this->createReader();
		$reader->readBlock(0, 0);
	}


	public function testGetFileSize(): void
	{
		$reader = $this->createReader();

		static::assertSame(19, $reader->getFileSize());
	}

	public function testGetFileSizeNotExist(): void
	{
		$this->expectException(IOException::class);

		$reader = new FileReader(__DIR__ . '/FileReaderTest.not_exist');
		$reader->getFileSize();
	}


	protected function createReader(): FileReader
	{
		return new FileReader(__DIR__ . '/FileReaderTest.data');
	}
}
