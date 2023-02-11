<?php

declare(strict_types=1);

namespace AL\PhpWndb\Tests\Storage;

use AL\PhpWndb\Storage\FileStream;
use AL\PhpWndb\Storage\StringStream;
use PHPUnit\Framework\TestCase;

class StringStreamTest extends TestCase
{
    public function testReadAndSeek(): void
    {
        $stream = new StringStream("0123456789\na b c d e f g\n");

        self::assertSame('0', $stream->read(1));
        $stream->seek(13);
        self::assertSame(13, $stream->tell());
        self::assertSame("b c d e f g\n", $stream->read(1024));
        self::assertSame(25, $stream->tell());
        $stream->seek(2048);
        self::assertSame(25, $stream->tell());
        self::assertSame('', $stream->read(10));
        self::assertSame(25, $stream->tell());
        $stream->seek(7);
        self::assertSame(7, $stream->tell());
        self::assertSame("789\na", $stream->read(5));
        self::assertSame(12, $stream->tell());
    }
}
