<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Synsets;

use AL\PhpEnum\Enum;
use AL\PhpWndb\DataMapping\LemmaMapperInterface;
use AL\PhpWndb\DataMapping\PartOfSpeechMapperInterface;
use AL\PhpWndb\DataMapping\RelationPointerTypeMapperInterface;
use AL\PhpWndb\DataMapping\SynsetCategoryMapperInterface;
use AL\PhpWndb\Model\Exceptions\SynsetCreateException;
use AL\PhpWndb\Model\Relations\RelationPointerFactoryInterface;
use AL\PhpWndb\Model\Relations\RelationsFactoryInterface;
use AL\PhpWndb\Model\Relations\RelationsInterface;
use AL\PhpWndb\Model\Synsets\Adjectives\SynsetAdjectives;
use AL\PhpWndb\Model\Synsets\Adverbs\SynsetAdverbs;
use AL\PhpWndb\Model\Synsets\Nouns\SynsetNouns;
use AL\PhpWndb\Model\Synsets\Verbs\SynsetVerbs;
use AL\PhpWndb\Model\Words\AdjectiveInterface;
use AL\PhpWndb\Model\Words\AdverbInterface;
use AL\PhpWndb\Model\Words\NounInterface;
use AL\PhpWndb\Model\Words\VerbInterface;
use AL\PhpWndb\Model\Words\WordFactoryInterface;
use AL\PhpWndb\Model\Words\WordInterface;
use AL\PhpWndb\Parsing\ParsedData\ParsedFrameDataInterface;
use AL\PhpWndb\Parsing\ParsedData\ParsedPointerDataInterface;
use AL\PhpWndb\Parsing\ParsedData\ParsedSynsetDataInterface;
use AL\PhpWndb\Parsing\ParsedData\ParsedWordDataInterface;
use AL\PhpWndb\PartOfSpeechEnum;
use UnexpectedValueException;
use InvalidArgumentException;
use OutOfRangeException;

class SynsetFactory implements SynsetFactoryInterface
{
	/** @var SynsetCategoryMapperInterface */
	protected $synsetCategoryMapper;

	/** @var PartOfSpeechMapperInterface */
	protected $partOfSpeechMapper;

	/** @var RelationPointerTypeMapperInterface */
	protected $relationPointerTypeMapper;

	/** @var LemmaMapperInterface */
	protected $lemmaMapper;

	/** @var RelationsFactoryInterface */
	protected $relationsFactory;

	/** @var RelationPointerFactoryInterface */
	protected $relationPointerFactory;

	/** @var WordFactoryInterface */
	protected $wordFactory;


	public function __construct(
		SynsetCategoryMapperInterface $synsetCategoryMapper,
		PartOfSpeechMapperInterface $partOfSpeechMapper,
		RelationPointerTypeMapperInterface $relationPointerTypeMapper,
		LemmaMapperInterface $lemmaMapper,
		RelationsFactoryInterface $relationsFactory,
		RelationPointerFactoryInterface $relationPointerFactory,
		WordFactoryInterface $wordFactory
	) {
		$this->synsetCategoryMapper = $synsetCategoryMapper;
		$this->partOfSpeechMapper = $partOfSpeechMapper;
		$this->relationPointerTypeMapper = $relationPointerTypeMapper;
		$this->lemmaMapper = $lemmaMapper;
		$this->relationsFactory = $relationsFactory;
		$this->relationPointerFactory = $relationPointerFactory;
		$this->wordFactory = $wordFactory;
	}


	public function createSynsetFromParsedData(ParsedSynsetDataInterface $parsedSynsetData): SynsetInterface
	{
		try {
			$wordsData = $parsedSynsetData->getWords();
			$synsetOffset = $parsedSynsetData->getSynsetOffset();
			$gloss = $parsedSynsetData->getGloss();

			$partOfSpeech = $this->partOfSpeechMapper->tokenToPartOfSpeech($parsedSynsetData->getPartOfSpeech());
			
			$pointers = $this->createPointers($partOfSpeech, $parsedSynsetData->getPointers(), count($wordsData));
			$frames = $this->createFrames($partOfSpeech, $parsedSynsetData->getFrames(), count($wordsData));
			$words = $this->createWords($partOfSpeech, $wordsData, $pointers, $frames);

			return $this->createSynset($partOfSpeech, $synsetOffset, $gloss, $words, $parsedSynsetData->getLexFileNumber());
		}
		catch (\Throwable $e) {
			throw new SynsetCreateException('Create synset failed: ' . $e->getMessage(), 0, $e);
		}
	}


	/**
	 * @param WordInterface[] $words
	 * @throws UnexpectedValueException
	 */
	protected function createSynset(PartOfSpeechEnum $partOfSpeech, int $synsetOffset, string $gloss, array $words, int $synsetCategoryData): SynsetInterface
	{
		switch ($partOfSpeech) {
			case PartOfSpeechEnum::ADJECTIVE():
				$synsetCategory = $this->synsetCategoryMapper->mapSynsetAdjectivesCategory($synsetCategoryData);
				/** @var AdjectiveInterface[] $words */
				return new SynsetAdjectives($synsetOffset, $gloss, $words, $synsetCategory);

			case PartOfSpeechEnum::ADVERB():
				$synsetCategory = $this->synsetCategoryMapper->mapSynsetAdverbsCategory($synsetCategoryData);
				/** @var AdverbInterface[] $words */
				return new SynsetAdverbs($synsetOffset, $gloss, $words, $synsetCategory);

			case PartOfSpeechEnum::NOUN():
				$synsetCategory = $this->synsetCategoryMapper->mapSynsetNounsCategory($synsetCategoryData);
				/** @var NounInterface[] $words */
				return new SynsetNouns($synsetOffset, $gloss, $words, $synsetCategory);

			case PartOfSpeechEnum::VERB():
				$synsetCategory = $this->synsetCategoryMapper->mapSynsetVerbsCategory($synsetCategoryData);
				/** @var VerbInterface[] $words */
				return new SynsetVerbs($synsetOffset, $gloss, $words, $synsetCategory);

			default:
				throw new UnexpectedValueException("Unexpected part of speech: $partOfSpeech");
		}
	}

	/**
	 * @param ParsedPointerDataInterface[] $pointersData
	 */
	protected function createPointers(PartOfSpeechEnum $sourcePartOfSpeech, array $pointersData, int $wordsCount): ArraysHolder
	{
		$pointers = new ArraysHolder($wordsCount);
		foreach ($pointersData as $pointerData) {
			$pointerType = $this->relationPointerTypeMapper->tokenToRelationPointerType($pointerData->getPointerType(), $sourcePartOfSpeech);
			$targetPartOfSpeech = $this->partOfSpeechMapper->tokenToPartOfSpeech($pointerData->getPartOfSpeech());

			$pointer = $this->relationPointerFactory->createRelationPointer(
				$pointerType,
				$targetPartOfSpeech,
				$pointerData->getSynsetOffset(),
				$pointerData->getTargetWordIndex() ?: null
			);
			$index = $pointerData->getSourceWordIndex() - 1;

			$pointers->add($index, $pointer);
		}

		return $pointers;
	}

	/**
	 * @param ParsedFrameDataInterface[] $framesData
	 */
	protected function createFrames(PartOfSpeechEnum $sourcePartOfSpeech, array $framesData, int $wordsCount): ArraysHolder
	{
		if ($sourcePartOfSpeech !== PartOfSpeechEnum::VERB() && !empty($framesData)) {
			throw new InvalidArgumentException("There should not be any frames for part of speech $sourcePartOfSpeech.");
		}

		$frames = new ArraysHolder($wordsCount);
		foreach ($framesData as $frameData) {
			$frames->add(
				$frameData->getWordIndex() - 1,
				$frameData->getFrameNumber()
			);
		}

		return $frames;
	}

	/**
	 * @param ParsedWordDataInterface[] $wordsData
	 * @return WordInterface[]
	 */
	protected function createWords(PartOfSpeechEnum $partOfSpeech, array $wordsData, ArraysHolder $pointers, ArraysHolder $frames): array
	{
		$words = [];
		foreach ($wordsData as $key => $wordData) {
			$relations = $this->relationsFactory->createRelations($pointers->get($key));
			$words[] = $this->createWord($partOfSpeech, $wordData, $relations, $frames->get($key));
		}

		return $words;
	}

	/**
	 * @param int[] $frames
	 */
	protected function createWord(PartOfSpeechEnum $partOfSpeech, ParsedWordDataInterface $wordData, RelationsInterface $relations, array $frames): WordInterface
	{
		$lemma = $this->lemmaMapper->tokenToLemma($wordData->getValue());

		switch ($partOfSpeech) {
			case PartOfSpeechEnum::NOUN():      return $this->wordFactory->createNoun($lemma, $wordData->getLexId(), $relations);
			case PartOfSpeechEnum::VERB():      return $this->wordFactory->createVerb($lemma, $wordData->getLexId(), $relations, $frames);
			case PartOfSpeechEnum::ADJECTIVE(): return $this->wordFactory->createAdjective($lemma, $wordData->getLexId(), $relations);
			case PartOfSpeechEnum::ADVERB():    return $this->wordFactory->createAdverb($lemma, $wordData->getLexId(), $relations);
			default: throw new InvalidArgumentException("Unknown part of speech: $partOfSpeech");
		}
	}
}


/**
 * @internal
 */
class ArraysHolder
{
	/** @var array<int,mixed> */
	private $data = [];

	/** @var int */
	private $count;


	public function __construct(int $count)
	{
		$this->count = $count;
	}


	/**
	 * @param mixed $value
	 * @throws OutOfRangeException
	 */
	public function add(int $index, $value): void
	{
		if ($index >= $this->count) {
			throw new OutOfRangeException("Index ($index) has to be less than {$this->count}.");
		}

		if ($index < 0) {
			$this->addToAll($value);
		}
		else {
			$this->data[$index][] = $value;
		}
	}

	/**
	 * @return array<mixed>
	 */
	public function get(int $index): array
	{
		return $this->data[$index] ?? [];
	}


	/**
	 * @param mixed $value
	 */
	private function addToAll($value): void
	{
		for ($i = 0; $i < $this->count; ++$i) {
			$this->data[$i][] = $value;
		}
	}
}
