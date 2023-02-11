<?php

declare(strict_types=1);

namespace AL\PhpWndb\Search\Crawl;

use AL\PhpWndb\Model\Data\Synset;
use AL\PhpWndb\Repository\Data\SynsetRepository;

class SynsetListCrawlerFactory
{
    public function __construct(
        protected readonly SynsetRepository $synsetRepository,
        protected readonly SynsetCrawlerFactory $synsetCrawlerFactory,
    ) {
    }

    /**
     * @param Synset[] $synsets
     */
    public function createFromSynsets(array $synsets): SynsetListCrawler
    {
        return new SynsetListCrawler(
            synsets: $synsets,
            crawlerFactory: $this->synsetCrawlerFactory,
            listCrawlerFactory: $this,
        );
    }
}
