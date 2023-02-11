<?php

declare(strict_types=1);

namespace AL\PhpWndb\Repository\Index;

use AL\PhpWndb\Model\Index\IndexEntry;
use AL\PhpWndb\Model\Index\SyntacticCategory;

class IndexRepository
{
    /**
     * @var IndexSource[]
     */
    private readonly array $sources;

    /**
     * @param SyntacticCategory[] $sourceSyntacticCategories
     */
    public function __construct(
        IndexSourceFactory $indexSourceFactory,
        array $sourceSyntacticCategories,
    ) {
        $this->sources = \array_map(
            static fn (SyntacticCategory $syntacticCategory) => $indexSourceFactory->create($syntacticCategory),
            $sourceSyntacticCategories,
        );
    }

    /**
     * @return IndexEntry[]
     */
    public function findIndexEntry(string $lemma): array
    {
        $indexEntries = [];
        foreach ($this->sources as $source) {
            $indexEntry = $source->findIndexEntry($lemma);
            if ($indexEntry !== null) {
                $indexEntries[] = $indexEntry;
            }
        }

        return $indexEntries;
    }
}
