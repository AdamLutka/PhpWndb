<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests\DataStorage;

use AL\PhpWndb\DataStorage\FileReader;
use AL\PhpWndb\Tests\BaseTestAbstract;

class FileReaderTest extends BaseTestAbstract
{
	public function testReadAll(): void
	{
		$reader = new FileReader(__DIR__ . '/FileReaderTest.data');

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
}
