<?php
declare(strict_types=1);

namespace AL\PhpWndb\DataMapping;

use AL\PhpWndb\PartOfSpeechEnum;
use AL\PhpWndb\Model\Relations\RelationPointerTypeEnum;
use AL\PhpWndb\Model\Synsets\Adjectives\SynsetAdjectivesCategoryEnum;
use AL\PhpWndb\Model\Synsets\Adverbs\SynsetAdverbsCategoryEnum;
use AL\PhpWndb\Model\Synsets\Nouns\SynsetNounsCategoryEnum;
use AL\PhpWndb\Model\Synsets\Verbs\SynsetVerbsCategoryEnum;
use UnexpectedValueException;

interface SynsetDataMapperInterface
{
	/**
	 * @throws UnexpectedValueException
	 */
	public function mapPartOfSpeech(string $data): PartOfSpeechEnum;

	/**
	 * @throws UnexpectedValueException
	 */
	public function mapRelationPointerType(string $data, PartOfSpeechEnum $sourcePartOfSpeech): RelationPointerTypeEnum;


	/**
	 * @throws UnexpectedValueException
	 */
	public function mapSynsetAdjectivesCategory(int $data): SynsetAdjectivesCategoryEnum;

	/**
	 * @throws UnexpectedValueException
	 */
	public function mapSynsetAdverbsCategory(int $data): SynsetAdverbsCategoryEnum;

	/**
	 * @throws UnexpectedValueException
	 */
	public function mapSynsetNounsCategory(int $data): SynsetNounsCategoryEnum;

	/**
	 * @throws UnexpectedValueException
	 */
	public function mapSynsetVerbsCategory(int $data): SynsetVerbsCategoryEnum;
}
