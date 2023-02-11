<?php

declare(strict_types=1);

namespace AL\PhpWndb\Storage;

use InvalidArgumentException;

class StreamBinarySearcher implements StreamSearcher
{
    public function seekToLineStart(Stream $stream, string $lineStart): bool
    {
        if ($lineStart === '') {
            throw new InvalidArgumentException('`lineStart` is empty.');
        }

        $length = $stream->getLength();

        return $this->searchIn($stream, $lineStart, 0, $length);
    }

    protected function searchIn(Stream $stream, string $lineStart, int $start, int $end): bool
    {
        $lineStartLength = \strlen($lineStart);
        $distance = $end - $start;
        if ($distance < $lineStartLength) {
            return false;
        }

        $half = $start + (int) \floor($distance / 2);
        [$lineStartPosition, $line] = $this->findLine($stream, $half);

        $lineEndPosition = $lineStartPosition + \strlen($line);

        $cmp = \strncmp($line, $lineStart, $lineStartLength);
        if ($cmp > 0) {
            return $lineStartPosition >= $start
                && $this->searchIn($stream, $lineStart, $start, $lineStartPosition);
        } else if ($cmp < 0) {
            return $lineEndPosition < $end
                && $this->searchIn($stream, $lineStart, $lineEndPosition, $end);
        } else {
            $stream->seek($lineStartPosition + $lineStartLength);
            return true;
        }
    }

    /**
     * @return array{0: int, 1: string}
     */
    protected function findLine(Stream $stream, int $position): array
    {
        $backPart = $this->readLineBackward($stream, $position);
        $frontPart = $this->readLineForward($stream, $position);

        return [$position - \strlen($backPart), $backPart . $frontPart];
    }

    protected function readLineForward(Stream $stream, int $position): string
    {
        $line = '';
        $char = '';

        $stream->seek($position);

        do {
            $line .= $char;
            $char = $stream->read(1);
        } while ($char !== '' && $char !== "\n");

        return $line;
    }

    protected function readLineBackward(Stream $stream, int $position): string
    {
        $line = '';

        for ($i = $position - 1; $i >= 0; --$i) {
            $stream->seek($i);
            $char = $stream->read(1);
            if ($char === "\n") {
                break;
            }

            $line = $char . $line;
        }

        return $line;
    }
}
