<?php
declare(strict_types=1);

namespace AL\PhpWndb\Examples;

use AL\PhpWndb\DataStorage\MemoryConsumingSynsetDataLoader;
use AL\PhpWndb\DataStorage\MemoryConsumingWordIndexLoader;
use AL\PhpWndb\DiContainerFactory;

use function DI\create;
use function DI\get;

class MyDiContainerFactory extends DiContainerFactory
{
	protected function createServices(): array {
		$services = parent::createServices();

		// Synset loaders
		$services['noun.data.SynsetLoader'] = create(MemoryConsumingSynsetDataLoader::class)->constructor(get('noun.data.FileReader'));
		$services['verb.data.SynsetLoader'] = create(MemoryConsumingSynsetDataLoader::class)->constructor(get('verb.data.FileReader'));
		$services['adverb.data.SynsetLoader'] = create(MemoryConsumingSynsetDataLoader::class)->constructor(get('adverb.data.FileReader'));
		$services['adjective.data.SynsetLoader'] = create(MemoryConsumingSynsetDataLoader::class)->constructor(get('adjective.data.FileReader'));

		// Word index loaders
		$services['noun.index.WordIndexLoader'] = create(MemoryConsumingWordIndexLoader::class)->constructor(get('noun.index.FileReader'));
		$services['verb.index.WordIndexLoader'] = create(MemoryConsumingWordIndexLoader::class)->constructor(get('verb.index.FileReader'));
		$services['adverb.index.WordIndexLoader'] = create(MemoryConsumingWordIndexLoader::class)->constructor(get('adverb.index.FileReader'));
		$services['adjective.index.WordIndexLoader'] = create(MemoryConsumingWordIndexLoader::class)->constructor(get('adjective.index.FileReader'));

		return $services;
	}
}
