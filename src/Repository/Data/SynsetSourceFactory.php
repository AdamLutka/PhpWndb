<?php

declare(strict_types=1);

namespace AL\PhpWndb\Repository\Data;

use AL\PhpWndb\Model\Index\SyntacticCategory;
use AL\PhpWndb\Parser\SynsetParser;
use AL\PhpWndb\Storage\Storage;

class SynsetSourceFactory
{
    public function __construct(
        protected readonly Storage $storage,
        protected readonly SynsetParser $synsetParser,
    ) {
    }

    public function create(SyntacticCategory $syntacticCategory): SynsetSource
    {
        return new SynsetSource(
            syntacticCategory: $syntacticCategory,
            stream: $this->storage->openDataStream($syntacticCategory),
            parser: $this->synsetParser,
        );
    }
}
