<?php

declare(strict_types=1);

namespace AL\PhpWndb;

use AL\PhpWndb\DI\DiContainerFactory;
use Psr\Container\ContainerInterface;

class WordNetProvider
{
    protected readonly DiContainerFactory $containerFactory;

    private ?ContainerInterface $container = null;

    public function __construct(
        protected readonly ?string $cacheDir = null,
        protected readonly bool $isDebug = false,
    )
    {
        $this->containerFactory = new DiContainerFactory();
    }

    public function getWordNet(): WordNet
    {
        $this->container ??= $this->cacheDir === null
            ? $this->containerFactory->create()
            : $this->containerFactory->createCached($this->cacheDir, $this->isDebug);

        $wordNet = $this->container->get(WordNet::class);

        \assert($wordNet instanceof WordNet);

        return $wordNet;
    }
}
