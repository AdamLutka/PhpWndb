<?php

declare(strict_types=1);

namespace AL\PhpWndb\Repository\Data;

use AL\PhpWndb\Model\Data\Synset;
use AL\PhpWndb\Model\Index\SyntacticCategory;

class SynsetRepository
{
    /** @var array<string, SynsetSource> */
    private array $sources = [];

    public function __construct(
        protected readonly SynsetSourceFactory $sourceFactory,
    ) {
    }

    public function getSynset(SyntacticCategory $syntacticCategory, int $synsetOffset): Synset
    {
        $key = $syntacticCategory->name;
        $this->sources[$key] ??= $this->sourceFactory->create($syntacticCategory);

        return $this->sources[$key]->getSynset($synsetOffset);
    }
}
