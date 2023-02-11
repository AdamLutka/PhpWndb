<?php

declare(strict_types=1);

namespace AL\PhpWndb\Model\SynsetId;

use AL\PhpWndb\Model\Index\SyntacticCategory;

class SynsetId
{
    public function __construct(
        protected readonly SyntacticCategory $syntacticCategory,
        protected readonly int $synsetOffset,
    ) {
    }

    public function getSyntacticCategory(): SyntacticCategory
    {
        return $this->syntacticCategory;
    }

    public function getSynsetOffset(): int
    {
        return $this->synsetOffset;
    }
}
