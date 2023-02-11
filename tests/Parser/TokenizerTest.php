<?php

declare(strict_types=1);

namespace AL\PhpWndb\Tests\Parser;

use AL\PhpWndb\Parser\Exception\ParseException;
use AL\PhpWndb\Parser\Tokenizer;
use AL\PhpWndb\Storage\StringStream;
use Iterator;
use PHPUnit\Framework\TestCase;

class TokenizerTest extends TestCase
{
    public function testUnexpectedEofReadString(): void
    {
        // end of file
        $tokenizer = $this->createTokenizer('');

        $this->expectException(ParseException::class);
        $this->expectExceptionMessage('Offset 0: string token expected');

        $tokenizer->readString();
    }

    /**
     * @dataProvider dpTestReadString
     */
    public function testReadString(string $input, string $output): void
    {
        $tokenizer = $this->createTokenizer($input);
        self::assertSame($output, $tokenizer->readString());
    }

    /**
     * @return Iterator<mixed>
     */
    public static function dpTestReadString(): Iterator
    {
        yield ['abc 123', 'abc'];
        yield ['123 abc', '123'];
        yield ['123abc ', '123abc'];
        yield ["123\nabc", '123'];
    }

    public function testUnexpectedEofReadDecimalInteger(): void
    {
        // end of file
        $tokenizer = $this->createTokenizer('');

        $this->expectException(ParseException::class);
        $this->expectExceptionMessage('Offset 0: decimal integer expected');

        $tokenizer->readDecimalInteger();
    }

    public function testExpectedDecimalInteger(): void
    {
        $tokenizer = $this->createTokenizer('abc ');

        $this->expectException(ParseException::class);
        $this->expectExceptionMessage('Offset 1: decimal integer expected');

        $tokenizer->readDecimalInteger();
    }

    /**
     * @dataProvider dpTestDecimalInteger
     */
    public function testDecimalInteger(string $input, ?int $length, int $output): void
    {
        $tokenizer = $this->createTokenizer($input);
        self::assertSame($output, $tokenizer->readDecimalInteger($length));
    }

    /**
     * @return Iterator<mixed>
     */
    public static function dpTestDecimalInteger(): Iterator
    {
        yield ['123 ', null, 123];
        yield ['123 abc', null, 123];
        yield ['123abc ', null, 123];
        yield ["123\nabc", null, 123];
        yield ['123 abc', 5, 123];
        yield ['123 abc', 2, 12];
        yield ['123 abc', 0, 0];
    }

    /**
     * @dataProvider dpTestHexInteger
     */
    public function testHexInteger(string $input, ?int $length, int $output): void
    {
        $tokenizer = $this->createTokenizer($input);
        self::assertSame($output, $tokenizer->readHexInteger($length));
    }

    /**
     * @return Iterator<mixed>
     */
    public static function dpTestHexInteger(): Iterator
    {
        yield ['123 ', null, 291];
        yield ['123 abc', null, 291];
        yield ['123abc ', null, 1194684];
        yield ["123\nabc", null, 291];
        yield ['123 abc', 5, 291];
        yield ['123 abc', 2, 18];
        yield ['123 abc', 0, 0];
    }

    /**
     * @dataProvider dpTestReadRestOfLine
     */
    public function testReadRestOfLine(string $input, string $output): void
    {
        $tokenizer = $this->createTokenizer($input);
        self::assertSame($output, $tokenizer->readRestOfLine());
    }

    /**
     * @return Iterator<mixed>
     */
    public static function dpTestReadRestOfLine(): Iterator
    {
        yield ['', ''];
        yield ['123', '123'];
        yield ['abc', 'abc'];
        yield ["abc \n 123", 'abc '];
    }

    private function createTokenizer(string $streamData): Tokenizer
    {
        $stream = new StringStream($streamData);

        return new Tokenizer($stream);
    }
}
