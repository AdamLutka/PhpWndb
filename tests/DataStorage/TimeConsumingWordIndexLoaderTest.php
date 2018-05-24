<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests\DataStorage;

use AL\PhpWndb\DataStorage\FileBinarySearcherInterface;
use AL\PhpWndb\DataStorage\TimeConsumingWordIndexLoader;
use AL\PhpWndb\Tests\BaseTestAbstract;

class TimeConsumingWordIndexLoaderTest extends BaseTestAbstract
{
	public function testFindLemmaIndexData(): void
	{
		$input = 'input';
		$output = 'output';

		$reader = $this->createMock(FileBinarySearcherInterface::class);
		$reader->method('searchFor')->with($input)->willReturn($output);

		$loader = new TimeConsumingWordIndexLoader($reader);

		static::assertSame($output, $loader->findLemmaIndexData($input));
	}
}
