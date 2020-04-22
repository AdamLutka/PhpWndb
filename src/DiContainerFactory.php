<?php
declare(strict_types=1);

namespace AL\PhpWndb;

use AL\PhpWndb\Cache\CacheInterface;
use AL\PhpWndb\Cache\MemoryCache;
use AL\PhpWndb\DataMapping\LemmaMapper;
use AL\PhpWndb\DataMapping\LemmaMapperInterface;
use AL\PhpWndb\DataMapping\PartOfSpeechMapper;
use AL\PhpWndb\DataMapping\PartOfSpeechMapperInterface;
use AL\PhpWndb\DataMapping\RelationPointerTypeMapper;
use AL\PhpWndb\DataMapping\RelationPointerTypeMapperInterface;
use AL\PhpWndb\DataMapping\SynsetCategoryMapper;
use AL\PhpWndb\DataMapping\SynsetCategoryMapperInterface;
use AL\PhpWndb\DataStorage\FileBinarySearcher;
use AL\PhpWndb\DataStorage\FileReader;
use AL\PhpWndb\DataStorage\TimeConsumingSynsetDataLoader;
use AL\PhpWndb\DataStorage\TimeConsumingWordIndexLoader;
use AL\PhpWndb\Model\Indices\Collections\WordIndexCollectionFactory;
use AL\PhpWndb\Model\Indices\Collections\WordIndexCollectionFactoryInterface;
use AL\PhpWndb\Model\Indices\WordIndexFactory;
use AL\PhpWndb\Model\Indices\WordIndexFactoryInterface;
use AL\PhpWndb\Model\Relations\RelationPointerFactory;
use AL\PhpWndb\Model\Relations\RelationPointerFactoryInterface;
use AL\PhpWndb\Model\Relations\RelationsFactory;
use AL\PhpWndb\Model\Relations\RelationsFactoryInterface;
use AL\PhpWndb\Model\Synsets\Collections\SynsetCollectionFactory;
use AL\PhpWndb\Model\Synsets\Collections\SynsetCollectionFactoryInterface;
use AL\PhpWndb\Model\Synsets\SynsetFactory;
use AL\PhpWndb\Model\Synsets\SynsetFactoryInterface;
use AL\PhpWndb\Model\Words\WordFactory;
use AL\PhpWndb\Model\Words\WordFactoryInterface;
use AL\PhpWndb\Parsing\SynsetDataParser;
use AL\PhpWndb\Parsing\SynsetDataParserInterface;
use AL\PhpWndb\Parsing\WordIndexParser;
use AL\PhpWndb\Parsing\WordIndexParserInterface;
use AL\PhpWndb\PartOfSpeechEnum;
use AL\PhpWndb\Repositories\SynsetRepository;
use AL\PhpWndb\Repositories\SynsetMultiRepository;
use AL\PhpWndb\Repositories\SynsetMultiRepositoryInterface;
use AL\PhpWndb\Repositories\WordIndexMultiRepository;
use AL\PhpWndb\Repositories\WordIndexMultiRepositoryInterface;
use AL\PhpWndb\Repositories\WordIndexRepository;
use AL\PhpWndb\WordNet;

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

use function DI\autowire;
use function DI\create;
use function DI\get;
use function DI\string as diString;

class DiContainerFactory
{
	public function createContainer(): ContainerInterface
	{
		$builder = $this->createBuilderWithDefinitions();

		return $builder->build();
	}

	/**
	 * @param string $directory path of directory where result of compilation will be saved
	 */
	public function createCompiledContainer(string $directory): ContainerInterface
	{
		$builder = $this->createBuilderWithDefinitions();
		$builder->enableCompilation($directory);

		return $builder->build();
	}


	protected function createBuilderWithDefinitions(): ContainerBuilder
	{
		$builder = new ContainerBuilder();
		$builder->addDefinitions($this->createServices());
		$builder->addDefinitions($this->createParameters());

		return $builder;
	}

	/**
	 * @return array<string,mixed>
	 */
	protected function createParameters(): array
	{
		return [
			'paths.dbDirectory'     => __DIR__ . '/../wordnet-db',
			'paths.noun.data'       => diString('{paths.dbDirectory}/data.noun'),
			'paths.noun.index'      => diString('{paths.dbDirectory}/index.noun'),
			'paths.verb.data'       => diString('{paths.dbDirectory}/data.verb'),
			'paths.verb.index'      => diString('{paths.dbDirectory}/index.verb'),
			'paths.adverb.data'     => diString('{paths.dbDirectory}/data.adv'),
			'paths.adverb.index'    => diString('{paths.dbDirectory}/index.adv'),
			'paths.adjective.data'  => diString('{paths.dbDirectory}/data.adj'),
			'paths.adjective.index' => diString('{paths.dbDirectory}/index.adj'),

			'sizes.readBlock.synset'    => 16 * 1024,  // 16 KiB
			'sizes.readBlock.wordIndex' => 256 * 1024, // 256 KiB

			'cache.memory.maxItemsCount' => 100,

			'format.wordIndex.recordSeparator'       => "\n",
			'format.wordIndex.recordPrefixSeparator' => ' ',
		];
	}

	/**
	 * @return array<string,mixed>
	 */
	protected function createServices(): array
	{
		return [
			// Cache
			CacheInterface::class => create(MemoryCache::class)->constructor(get('cache.memory.maxItemsCount')),

			// Data mappers
			LemmaMapperInterface::class => autowire(LemmaMapper::class),
			PartOfSpeechMapperInterface::class => create(PartOfSpeechMapper::class),
			RelationPointerTypeMapperInterface::class => create(RelationPointerTypeMapper::class),
			SynsetCategoryMapperInterface::class => create(SynsetCategoryMapper::class),

			// Synset file readers
			'noun.data.FileReader' => create(FileReader::class)->constructor(get('paths.noun.data')),
			'verb.data.FileReader' => create(FileReader::class)->constructor(get('paths.verb.data')),
			'adverb.data.FileReader' => create(FileReader::class)->constructor(get('paths.adverb.data')),
			'adjective.data.FileReader' => create(FileReader::class)->constructor(get('paths.adjective.data')),

			// Synset loaders
			'noun.data.SynsetLoader' => create(TimeConsumingSynsetDataLoader::class)->constructor(get('noun.data.FileReader'), get('sizes.readBlock.synset')),
			'verb.data.SynsetLoader' => create(TimeConsumingSynsetDataLoader::class)->constructor(get('verb.data.FileReader'), get('sizes.readBlock.synset')),
			'adverb.data.SynsetLoader' => create(TimeConsumingSynsetDataLoader::class)->constructor(get('adverb.data.FileReader'), get('sizes.readBlock.synset')),
			'adjective.data.SynsetLoader' => create(TimeConsumingSynsetDataLoader::class)->constructor(get('adjective.data.FileReader'), get('sizes.readBlock.synset')),

			// Synset repositories
			'noun.data.Repository' => autowire(SynsetRepository::class)->constructorParameter('dataLoader', get('noun.data.SynsetLoader')),
			'verb.data.Repository' => autowire(SynsetRepository::class)->constructorParameter('dataLoader', get('verb.data.SynsetLoader')),
			'adverb.data.Repository' => autowire(SynsetRepository::class)->constructorParameter('dataLoader', get('adverb.data.SynsetLoader')),
			'adjective.data.Repository' => autowire(SynsetRepository::class)->constructorParameter('dataLoader', get('adjective.data.SynsetLoader')),

			SynsetMultiRepositoryInterface::class => function(ContainerInterface $container) {
					$repository = new SynsetMultiRepository();
					$repository->addRepository(PartOfSpeechEnum::NOUN(), $container->get('noun.data.Repository'));
					$repository->addRepository(PartOfSpeechEnum::VERB(), $container->get('verb.data.Repository'));
					$repository->addRepository(PartOfSpeechEnum::ADJECTIVE(), $container->get('adjective.data.Repository'));
					$repository->addRepository(PartOfSpeechEnum::ADVERB(), $container->get('adverb.data.Repository'));

					return $repository;
				},

			// Word index file readers
			'noun.index.FileReader' => create(FileReader::class)->constructor(get('paths.noun.index')),
			'verb.index.FileReader' => create(FileReader::class)->constructor(get('paths.verb.index')),
			'adverb.index.FileReader' => create(FileReader::class)->constructor(get('paths.adverb.index')),
			'adjective.index.FileReader' => create(FileReader::class)->constructor(get('paths.adjective.index')),

			// Word index binary searchers
			'noun.index.FileBinarySearcher' => create(FileBinarySearcher::class)->constructor(
					get('noun.index.FileReader'),
					get('format.wordIndex.recordSeparator'),
					get('format.wordIndex.recordPrefixSeparator'),
					get('sizes.readBlock.wordIndex')
				),
			'verb.index.FileBinarySearcher' => create(FileBinarySearcher::class)->constructor(
					get('verb.index.FileReader'),
					get('format.wordIndex.recordSeparator'),
					get('format.wordIndex.recordPrefixSeparator'),
					get('sizes.readBlock.wordIndex')
				),
			'adverb.index.FileBinarySearcher' => create(FileBinarySearcher::class)->constructor(
					get('adverb.index.FileReader'),
					get('format.wordIndex.recordSeparator'),
					get('format.wordIndex.recordPrefixSeparator'),
					get('sizes.readBlock.wordIndex')
				),
			'adjective.index.FileBinarySearcher' => create(FileBinarySearcher::class)->constructor(
					get('adjective.index.FileReader'),
					get('format.wordIndex.recordSeparator'),
					get('format.wordIndex.recordPrefixSeparator'),
					get('sizes.readBlock.wordIndex')
				),

			// Word index loaders
			'noun.index.WordIndexLoader' => create(TimeConsumingWordIndexLoader::class)->constructor(get('noun.index.FileBinarySearcher')),
			'verb.index.WordIndexLoader' => create(TimeConsumingWordIndexLoader::class)->constructor(get('verb.index.FileBinarySearcher')),
			'adverb.index.WordIndexLoader' => create(TimeConsumingWordIndexLoader::class)->constructor(get('adverb.index.FileBinarySearcher')),
			'adjective.index.WordIndexLoader' => create(TimeConsumingWordIndexLoader::class)->constructor(get('adjective.index.FileBinarySearcher')),

			// Word index repositories
			'noun.index.Repository' => autowire(WordIndexRepository::class)->constructorParameter('wordIndexLoader', get('noun.index.WordIndexLoader')),
			'verb.index.Repository' => autowire(WordIndexRepository::class)->constructorParameter('wordIndexLoader', get('verb.index.WordIndexLoader')),
			'adverb.index.Repository' => autowire(WordIndexRepository::class)->constructorParameter('wordIndexLoader', get('adverb.index.WordIndexLoader')),
			'adjective.index.Repository' => autowire(WordIndexRepository::class)->constructorParameter('wordIndexLoader', get('adjective.index.WordIndexLoader')),

			WordIndexMultiRepositoryInterface::class => function(ContainerInterface $container) {
					$repository = new WordIndexMultiRepository();
					$repository->addRepository(PartOfSpeechEnum::NOUN(),      $container->get('noun.index.Repository'));
					$repository->addRepository(PartOfSpeechEnum::VERB(),      $container->get('verb.index.Repository'));
					$repository->addRepository(PartOfSpeechEnum::ADJECTIVE(), $container->get('adjective.index.Repository'));
					$repository->addRepository(PartOfSpeechEnum::ADVERB(),    $container->get('adverb.index.Repository'));

					return $repository;
				},

			// ...
			SynsetCollectionFactoryInterface::class => autowire(SynsetCollectionFactory::class),
			WordIndexCollectionFactoryInterface::class => autowire(WordIndexCollectionFactory::class),
			SynsetDataParserInterface::class => create(SynsetDataParser::class),
			RelationsFactoryInterface::class => create(RelationsFactory::class),
			RelationPointerFactoryInterface::class => create(RelationPointerFactory::class),
			WordFactoryInterface::class => create(WordFactory::class),
			SynsetFactoryInterface::class => autowire(SynsetFactory::class),
			WordIndexParserInterface::class => create(WordIndexParser::class),
			WordIndexFactoryInterface::class => autowire(WordIndexFactory::class),
			WordNet::class => autowire(WordNet::class),
		];
	}
}
