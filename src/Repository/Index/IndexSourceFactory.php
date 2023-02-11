<?php

declare(strict_types=1);

namespace AL\PhpWndb\Repository\Index;

use AL\PhpWndb\Model\Index\SyntacticCategory;
use AL\PhpWndb\Parser\IndexParser;
use AL\PhpWndb\Storage\Storage;
use AL\PhpWndb\Storage\StreamSearcher;

class IndexSourceFactory
{
    public function __construct(
        protected readonly Storage $storage,
        protected readonly StreamSearcher $streamSearcher,
        protected readonly IndexParser $indexParser,
    ) {
    }

    public function create(SyntacticCategory $syntacticCategory): IndexSource
    {
        return new IndexSource(
            stream: $this->storage->openIndexStream($syntacticCategory),
            searcher: $this->streamSearcher,
            parser: $this->indexParser,
        );
    }
}
