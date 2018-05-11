<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests\DataStorage;

use AL\PhpWndb\DataStorage\FileReader;
use AL\PhpWndb\Tests\BaseTestAbstract;

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

	/**
	 * @expectedException \AL\PhpWndb\Exceptions\IOException
	 */
	public function testReadAllNotExist(): void
	{
		$reader = new FileReader(__DIR__ . '/FileReaderTest.not_exist');
		$reader->readAll();
	}


	public function testReadBlock(): void
	{
		$reader = $this->createReader();

		static::assertSame("3\n\nb4", $reader->readBlock(3, 5));
		static::assertSame('a12', $reader->readBlock(0, 3));
	}

	/**
	 * @expectedException \AL\PhpWndb\Exceptions\IOException
	 */
	public function testReadBlockNotExist(): void
	{
		$reader = new FileReader(__DIR__ . '/FileReaderTest.not_exist');
		$reader->readAll();
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testReadBlockInvalidOffset(): void
	{
		$reader = $this->createReader();
		$reader->readBlock(-1, 10);
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testReadBlockInvalidSize(): void
	{
		$reader = $this->createReader();
		$reader->readBlock(0, 0);
	}


	protected function createReader(): FileReader
	{
		return new FileReader(__DIR__ . '/FileReaderTest.data');
	}
}
