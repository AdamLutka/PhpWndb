<?php

declare(strict_types=1);

namespace AL\PhpWndb;

use AL\PhpWndb\Search\Crawl\SynsetListCrawler;
use AL\PhpWndb\Search\Crawl\SynsetListCrawlerFactory;
use AL\PhpWndb\Search\Data\SynsetSearchEngine;
use AL\PhpWndb\Search\Index\IndexSearchEngine;

class WordNet
{
    public function __construct(
        protected readonly SynsetListCrawlerFactory $crawlerFactory,
        protected readonly IndexSearchEngine $indexSearchEngine,
        protected readonly SynsetSearchEngine $synsetSearchEngine,
    ) {
    }

    public function search(string $searchTerm): SynsetListCrawler
    {
        $synsetIds = $this->indexSearchEngine->search($searchTerm);
        $synsets = $this->synsetSearchEngine->listBySynsetIds($synsetIds);

        return $this->crawlerFactory->createFromSynsets($synsets);
    }
}
