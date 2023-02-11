<?php

declare(strict_types=1);

namespace AL\PhpWndb\Tests\Storage;

use AL\PhpWndb\Storage\FileStream;
use PHPUnit\Framework\TestCase;

class FileStreamTest extends TestCase
{
    public function testReadAndSeek(): void
    {
        $stream = FileStream::open(__DIR__ . '/Fixture/data.txt');

        self::assertSame(0, $stream->tell());
        self::assertSame('0', $stream->read(1));
        self::assertSame(1, $stream->tell());
        $stream->seek(13);
        self::assertSame(13, $stream->tell());
        self::assertSame("b c d e f g\n", $stream->read(1024));
        self::assertSame(25, $stream->tell());
        $stream->seek(2048);
        self::assertSame(2048, $stream->tell());
        self::assertSame('', $stream->read(10));
        self::assertSame(2048, $stream->tell());
        $stream->seek(7);
        self::assertSame(7, $stream->tell());
        self::assertSame("789\na", $stream->read(5));
        self::assertSame(12, $stream->tell());
    }

    public function testOpenUnknownFile(): void
    {
        $this->expectExceptionMessageMatches('~.*No such file or directory~');
        FileStream::open(__DIR__ . '/Fixture/unknown.file');
    }
}
