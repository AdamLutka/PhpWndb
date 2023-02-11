<?php

declare(strict_types=1);

namespace AL\PhpWndb\Parser;

use AL\PhpWndb\Parser\Exception\ParseException;
use AL\PhpWndb\Storage\Stream;

class Tokenizer
{
    public function __construct(
        protected readonly Stream $stream,
    ) {
    }

    /**
     * @throws ParseException
     */
    public function readRestOfLine(): string
    {
        $line = '';

        do {
            $char = $this->readChar();
            if ($char === "\n" || $char === '') {
                return $line;
            }
            $line .= $char;
        } while (true);
    }

    /**
     * @throws ParseException
     */
    public function readString(): string
    {
        $token = '';

        do {
            $char = $this->readChar();
            if ($char === ' ' || $char === "\n" || $char === '') {
                return $token !== ''
                    ? $token
                    : throw $this->createException("string token expected");
            }
            $token .= $char;
        } while (true);
    }

    /**
     * @throws ParseException
     */
    public function readDecimalInteger(?int $length = null): int
    {
        $number = 0;
        $charsCount = 0;

        while ($length === null || $charsCount < $length) {
            $char = $this->readChar();
            if ($char < '0' || $char > '9') {
                return $charsCount > 0
                    ? $number
                    : throw $this->createException('decimal integer expected');
            }
            $number = $number * 10 + (int)$char;
            ++$charsCount;
        }

        return $number;
    }

    /**
     * @throws ParseException
     */
    public function readHexInteger(?int $length = null): int
    {
        $token = '';

        while ($length === null || \strlen($token) < $length) {
            $char = $this->readChar();
            if ('0' <= $char && $char <= '9' || 'a' <= $char && $char <= 'z' || 'A' <= $char && $char <= 'Z') {
                $token .= $char;
                continue;
            }

            if ($token === '') {
                throw $this->createException('hex integer expected');
            }
            break;
        }

        return (int) \hexdec($token);
    }

    protected function readChar(): string
    {
        return $this->stream->read(1);
    }

    protected function createException(string $message): ParseException
    {
        return new ParseException("Offset {$this->stream->tell()}: $message");
    }
}
