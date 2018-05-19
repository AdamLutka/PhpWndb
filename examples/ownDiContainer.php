<?php
declare(strict_types=1);

use AL\PhpWndb\DataStorage\MemoryConsumingSynsetDataLoader;
use AL\PhpWndb\DataStorage\MemoryConsumingWordIndexLoader;
use AL\PhpWndb\DiContainerFactory;
use AL\PhpWndb\WordNet;

use function DI\create;
use function DI\get;

require_once(__DIR__ . '/../vendor/autoload.php');


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


$containerFactory = new MyDiContainerFactory();
$container = $containerFactory->createContainer();

/** @var WordNet */
$wordNet = $container->get(WordNet::class);

$synsets = $wordNet->searchLemma('cat');

foreach ($synsets as $synset) {
	echo "  " . $synset->getGloss() . "\n";
}
