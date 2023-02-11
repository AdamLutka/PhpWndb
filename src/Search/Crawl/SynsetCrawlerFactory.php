<?php

declare(strict_types=1);

namespace AL\PhpWndb\Search\Crawl;

use AL\PhpWndb\Model\Data\Synset;
use AL\PhpWndb\Search\Data\SynsetSearchEngine;

class SynsetCrawlerFactory
{
    public function __construct(
        protected readonly WordCrawlerFactory $wordCrawlerFactory,
        protected readonly SynsetSearchEngine $synsetSearchEngine,
    ) {
    }

    /**
     * @param Synset[] $synsets
     * @return SynsetCrawler[]
     */
    public function createFromSynsets(array $synsets, SynsetListCrawlerFactory $listCrawlerFactory): array
    {
        return \array_map(
            fn (Synset $synset) => $this->createFromSynset($synset, $listCrawlerFactory),
            $synsets,
        );
    }

    protected function createFromSynset(Synset $synset, SynsetListCrawlerFactory $listCrawlerFactory): SynsetCrawler
    {
        return new SynsetCrawler(
            synset: $synset,
            wordCrawlerFactory: $this->wordCrawlerFactory,
            synsetListCrawlerFactory: $listCrawlerFactory,
            synsetSearchEngine: $this->synsetSearchEngine,
        );
    }
}
