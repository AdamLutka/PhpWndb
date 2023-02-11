<?php

declare(strict_types=1);

namespace AL\PhpWndb\Storage;

interface Stream
{
    public function seek(int $offset): void;

    public function tell(): int;

    public function read(int $length): string;

    public function getLength(): int;
}
