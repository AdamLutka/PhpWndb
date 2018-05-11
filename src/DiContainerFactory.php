<?php
declare(strict_types=1);

namespace AL\PhpWndb;

use AL\PhpWndb\DataMapping\LemmaMapper;
use AL\PhpWndb\DataMapping\LemmaMapperInterface;
use AL\PhpWndb\DataMapping\PartOfSpeechMapper;
use AL\PhpWndb\DataMapping\PartOfSpeechMapperInterface;
use AL\PhpWndb\DataMapping\RelationPointerTypeMapper;
use AL\PhpWndb\DataMapping\RelationPointerTypeMapperInterface;
use AL\PhpWndb\DataMapping\SynsetCategoryMapper;
use AL\PhpWndb\DataMapping\SynsetCategoryMapperInterface;
use AL\PhpWndb\DataStorage\FileReader;
use AL\PhpWndb\DataStorage\MemoryConsumingSynsetDataLoader;
use AL\PhpWndb\DataStorage\MemoryConsumingWordIndexLoader;
use AL\PhpWndb\Model\Indexes\WordIndexFactory;
use AL\PhpWndb\Model\Indexes\WordIndexFactoryInterface;
use AL\PhpWndb\Model\Relations\RelationPointerFactory;
use AL\PhpWndb\Model\Relations\RelationPointerFactoryInterface;
use AL\PhpWndb\Model\Relations\RelationsFactory;
use AL\PhpWndb\Model\Relations\RelationsFactoryInterface;
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
use AL\PhpWndb\Repositories\WordIndexRepository;
use AL\PhpWndb\Repositories\WordIndexRepositoryInterface;
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
		];
	}

	protected function createServices(): array
	{
		return [
			// Data mappers
			LemmaMapperInterface::class => create(LemmaMapper::class),
			PartOfSpeechMapperInterface::class => create(PartOfSpeechMapper::class),
			RelationPointerTypeMapperInterface::class => create(RelationPointerTypeMapper::class),
			SynsetCategoryMapperInterface::class => create(SynsetCategoryMapper::class),

			// Synset file readers
			'noun.data.FileReader' => create(FileReader::class)->constructor(get('paths.noun.data')),
			'verb.data.FileReader' => create(FileReader::class)->constructor(get('paths.verb.data')),
			'adverb.data.FileReader' => create(FileReader::class)->constructor(get('paths.adverb.data')),
			'adjective.data.FileReader' => create(FileReader::class)->constructor(get('paths.adjective.data')),

			// Synset loaders
			'noun.data.SynsetLoader' => create(MemoryConsumingSynsetDataLoader::class)->constructor(get('noun.data.FileReader')),
			'verb.data.SynsetLoader' => create(MemoryConsumingSynsetDataLoader::class)->constructor(get('verb.data.FileReader')),
			'adverb.data.SynsetLoader' => create(MemoryConsumingSynsetDataLoader::class)->constructor(get('adverb.data.FileReader')),
			'adjective.data.SynsetLoader' => create(MemoryConsumingSynsetDataLoader::class)->constructor(get('adjective.data.FileReader')),

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

			// Word index loaders
			'noun.index.WordIndexLoader' => create(MemoryConsumingWordIndexLoader::class)->constructor(get('noun.index.FileReader')),
			'verb.index.WordIndexLoader' => create(MemoryConsumingWordIndexLoader::class)->constructor(get('verb.index.FileReader')),
			'adverb.index.WordIndexLoader' => create(MemoryConsumingWordIndexLoader::class)->constructor(get('adverb.index.FileReader')),
			'adjective.index.WordIndexLoader' => create(MemoryConsumingWordIndexLoader::class)->constructor(get('adjective.index.FileReader')),

			// Word index repositories
			'noun.index.Repository' => autowire(WordIndexRepository::class)->constructorParameter('wordIndexLoader', get('noun.index.WordIndexLoader')),
			'verb.index.Repository' => autowire(WordIndexRepository::class)->constructorParameter('wordIndexLoader', get('verb.index.WordIndexLoader')),
			'adverb.index.Repository' => autowire(WordIndexRepository::class)->constructorParameter('wordIndexLoader', get('adverb.index.WordIndexLoader')),
			'adjective.index.Repository' => autowire(WordIndexRepository::class)->constructorParameter('wordIndexLoader', get('adjective.index.WordIndexLoader')),

			WordIndexRepositoryInterface::class => function(ContainerInterface $container) {
					return new WordIndexMultiRepository([
						$container->get('noun.index.Repository'),
						$container->get('verb.index.Repository'),
						$container->get('adjective.index.Repository'),
						$container->get('adverb.index.Repository'),
					]);
				},

			// ...
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
