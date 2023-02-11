<?php

declare(strict_types=1);

namespace AL\PhpWndb\Search\Index;

use AL\PhpWndb\Model\Index\IndexEntry;
use AL\PhpWndb\Model\SynsetId\SynsetId;
use AL\PhpWndb\Model\SynsetId\SynsetIdFactory;
use AL\PhpWndb\Repository\Index\IndexRepository;

class IndexSearchEngine
{
    public function __construct(
        protected readonly LemmaFactory $lemmaFactory,
        protected readonly IndexRepository $indexRepository,
        protected readonly SynsetIdFactory $synsetIdFactory,
    ) {
    }

    /**
     * @return SynsetId[]
     */
    public function search(string $searchTerm): array
    {
        $lemma = $this->lemmaFactory->create($searchTerm);
        $indexEntries = $this->indexRepository->findIndexEntry($lemma);
        $synsetIds = \array_map(
            fn (IndexEntry $indexEntry) => $this->synsetIdFactory->createFromIndexEntry($indexEntry),
            $indexEntries,
        );

        return \array_merge(...$synsetIds);
    }
}
