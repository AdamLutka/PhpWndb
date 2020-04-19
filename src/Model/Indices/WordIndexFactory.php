<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Indices;

use AL\PhpWndb\DataMapping\LemmaMapperInterface;
use AL\PhpWndb\DataMapping\PartOfSpeechMapperInterface;
use AL\PhpWndb\DataMapping\RelationPointerTypeMapperInterface;
use AL\PhpWndb\Parsing\ParsedData\ParsedWordIndexInterface;

class WordIndexFactory implements WordIndexFactoryInterface
{
	/** @var LemmaMapperInterface */
	protected $lemmaMapper;

	/** @var PartOfSpeechMapperInterface */
	protected $partOfSpeechMapper;

	/** @var RelationPointerTypeMapperInterface */
	protected $relationPointerTypeMapper;


	public function __construct(
		LemmaMapperInterface $lemmaMapper,
		PartOfSpeechMapperInterface $partOfSpeechMapper,
		RelationPointerTypeMapperInterface $relationPointerTypeMapper
	) {
		$this->lemmaMapper = $lemmaMapper;
		$this->partOfSpeechMapper = $partOfSpeechMapper;
		$this->relationPointerTypeMapper = $relationPointerTypeMapper;
	}


	public function createWordIndexFromParsedData(ParsedWordIndexInterface $parsedWordIndex): WordIndexInterface
	{
		$lemma = $this->lemmaMapper->tokenToLemma($parsedWordIndex->getLemma());
		$partOfSpeech = $this->partOfSpeechMapper->tokenToPartOfSpeech($parsedWordIndex->getPartOfSpeech());

		$relationPointerTypes = array_map(function (string $pointerType) use ($partOfSpeech) {
			return $this->relationPointerTypeMapper->tokenToRelationPointerType($pointerType, $partOfSpeech);
		}, $parsedWordIndex->getPointerTypes());

		return new WordIndex($lemma, $partOfSpeech, $relationPointerTypes, $parsedWordIndex->getSynsetOffsets());
	}
}
