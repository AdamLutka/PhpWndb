<?php

declare(strict_types=1);

namespace AL\PhpWndb\Search\Crawl;

use AL\PhpWndb\Model\Data\Synset;
use AL\PhpWndb\Model\Data\SynsetType;

/**
 * @extends ArrayCrawler<SynsetCrawler>
 */
class SynsetListCrawler extends ArrayCrawler
{
    /**
     * @param Synset[] $synsets
     */
    public function __construct(
        protected readonly array $synsets,
        protected readonly SynsetCrawlerFactory $crawlerFactory,
        protected readonly SynsetListCrawlerFactory $listCrawlerFactory,
    ) {
        parent::__construct($this->crawlerFactory->createFromSynsets($this->synsets, $this->listCrawlerFactory));
    }

    public function onlyType(SynsetType $synsetType): self
    {
        return $this->listCrawlerFactory->createFromSynsets(\array_filter(
            $this->synsets,
            static fn (Synset $synset) => $synset->getType() === $synsetType,
        ));
    }

    public function omitType(SynsetType $synsetType): self
    {
        return $this->listCrawlerFactory->createFromSynsets(\array_filter(
            $this->synsets,
            static fn (Synset $synset) => $synset->getType() !== $synsetType,
        ));
    }
}
