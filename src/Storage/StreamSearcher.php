<?php

declare(strict_types=1);

namespace AL\PhpWndb\Storage;

interface StreamSearcher
{
    public function seekToLineStart(Stream $stream, string $lineStart): bool;
}
