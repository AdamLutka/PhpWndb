<?php

declare(strict_types=1);

namespace AL\PhpWndb\Repository\Index;

use AL\PhpWndb\Model\Index\IndexEntry;
use AL\PhpWndb\Parser\IndexParser;
use AL\PhpWndb\Storage\Stream;
use AL\PhpWndb\Storage\StreamSearcher;

class IndexSource
{
    public function __construct(
        protected readonly Stream $stream,
        protected readonly StreamSearcher $searcher,
        protected readonly IndexParser $parser,
    ) {
    }

    public function findIndexEntry(string $lemma): ?IndexEntry
    {
        $found = $this->searcher->seekToLineStart($this->stream, "{$lemma} ");

        return $found ? $this->parser->parseIndex($this->stream) : null;
    }
}
