<?php

declare(strict_types=1);

namespace AL\PhpWndb\Storage;

class StringStream implements Stream
{
    private int $index = 0;

    public function __construct(
        protected readonly string $data,
    ) {
    }

    public function seek(int $offset): void
    {
        $this->index = \min($offset, $this->getLength());
    }

    public function tell(): int
    {
        return $this->index;
    }

    public function read(int $length): string
    {
        $data = \substr($this->data, $this->index, $length);
        $this->seek($this->index + $length);

        return $data;
    }

    public function getLength(): int
    {
        return \strlen($this->data);
    }
}
