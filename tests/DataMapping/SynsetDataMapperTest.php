<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests\DataMapping;

use AL\PhpWndb\DataMapping\SynsetDataMapper;
use AL\PhpWndb\PartOfSpeechEnum;
use AL\PhpWndb\Model\Relations\RelationPointerTypeEnum;
use AL\PhpWndb\Model\Synsets\Adjectives\SynsetAdjectivesCategoryEnum;
use AL\PhpWndb\Model\Synsets\Adverbs\SynsetAdverbsCategoryEnum;
use AL\PhpWndb\Model\Synsets\Nouns\SynsetNounsCategoryEnum;
use AL\PhpWndb\Model\Synsets\Verbs\SynsetVerbsCategoryEnum;
use AL\PhpWndb\Tests\AbstractBaseTest;

class SynsetDataMapperTest extends AbstractBaseTest
{
	/**
	 * @dataProvider dpTestMapPartOfSpeech
	 */
	public function testMapPartOfSpeech(PartOfSpeechEnum $expectedPartOfSpeech, string $data): void
	{
		$mapper = new SynsetDataMapper();

		static::assertEnum($expectedPartOfSpeech, $mapper->mapPartOfSpeech($data));
	}

	public function dpTestMapPartOfSpeech(): array
	{
		return [
			[PartOfSpeechEnum::NOUN(),                'n'],
			[PartOfSpeechEnum::VERB(),                'v'],
			[PartOfSpeechEnum::ADJECTIVE(),           'a'],
			[PartOfSpeechEnum::ADJECTIVE_SATELLITE(), 's'],
			[PartOfSpeechEnum::ADVERB(),              'r'],
		];
	}

	/**
	 * @expectedException \UnexpectedValueException
	 */
	public function testMapPartOfSpeechUnknown(): void
	{
		$mapper = new SynsetDataMapper();
		$mapper->mapPartOfSpeech('not_exist');
	}


	/**
	 * @dataProvider dpTestMapRelationPointerType
	 */
	public function testMapRelationPointerType(
		RelationPointerTypeEnum $expectedRelationPointerType,
		string $data,
		PartOfSpeechEnum $sourcePartOfSpeech
	): void {
		$mapper = new SynsetDataMapper();

		static::assertEnum($expectedRelationPointerType, $mapper->mapRelationPointerType($data, $sourcePartOfSpeech));
	}

	public function dpTestMapRelationPointerType(): array
	{
		return [
			[RelationPointerTypeEnum::ANTONYM(),                         '!', PartOfSpeechEnum::NOUN()],
			[RelationPointerTypeEnum::HYPERNYM(),                        '@', PartOfSpeechEnum::NOUN()],
			[RelationPointerTypeEnum::INSTANCE_HYPERNYM(),              '@i', PartOfSpeechEnum::NOUN()],
			[RelationPointerTypeEnum::HYPONYM(),                         '~', PartOfSpeechEnum::NOUN()],
 			[RelationPointerTypeEnum::INSTANCE_HYPONYM(),               '~i', PartOfSpeechEnum::NOUN()],
 			[RelationPointerTypeEnum::MEMBER_HOLONYM(),                 '#m', PartOfSpeechEnum::NOUN()],
 			[RelationPointerTypeEnum::SUBSTANCE_HOLONYM(),              '#s', PartOfSpeechEnum::NOUN()],
 			[RelationPointerTypeEnum::PART_HOLONYM(),                   '#p', PartOfSpeechEnum::NOUN()],
 			[RelationPointerTypeEnum::MEMBER_MERONYM(),                 '%m', PartOfSpeechEnum::NOUN()],
 			[RelationPointerTypeEnum::SUBSTANCE_MERONYM(),              '%s', PartOfSpeechEnum::NOUN()],
 			[RelationPointerTypeEnum::PART_MERONYM(),                   '%p', PartOfSpeechEnum::NOUN()],
			[RelationPointerTypeEnum::ATTRIBUTE(),                       '=', PartOfSpeechEnum::NOUN()],
			[RelationPointerTypeEnum::DERIVATIONALLY_RELATED_FORM(),     '+', PartOfSpeechEnum::NOUN()],
 			[RelationPointerTypeEnum::DOMAIN_OF_SYNSET_TOPIC(),         ';c', PartOfSpeechEnum::NOUN()],
 			[RelationPointerTypeEnum::MEMBER_OF_THIS_DOMAIN_TOPIC(),    '-c', PartOfSpeechEnum::NOUN()],
 			[RelationPointerTypeEnum::DOMAIN_OF_SYNSET_REGION(),        ';r', PartOfSpeechEnum::NOUN()],
 			[RelationPointerTypeEnum::MEMBER_OF_THIS_DOMAIN_REGION(),   '-r', PartOfSpeechEnum::NOUN()],
 			[RelationPointerTypeEnum::DOMAIN_OF_SYNSET_USAGE(),         ';u', PartOfSpeechEnum::NOUN()],
 			[RelationPointerTypeEnum::MEMBER_OF_THIS_DOMAIN_USAGE(),    '-u', PartOfSpeechEnum::NOUN()],
			[RelationPointerTypeEnum::ENTAILMENT(),                      '*', PartOfSpeechEnum::NOUN()],
			[RelationPointerTypeEnum::CAUSE(),                           '>', PartOfSpeechEnum::NOUN()],
			[RelationPointerTypeEnum::ALSO_SEE(),                        '^', PartOfSpeechEnum::NOUN()],
			[RelationPointerTypeEnum::VERB_GROUP(),                      '$', PartOfSpeechEnum::NOUN()],
			[RelationPointerTypeEnum::SIMILAR_TO(),                      '&', PartOfSpeechEnum::NOUN()],
			[RelationPointerTypeEnum::PARTICIPLE_OF_VERB(),              '<', PartOfSpeechEnum::NOUN()],
			[RelationPointerTypeEnum::DERIVED_FROM_ADJECTIVE(),         '\\', PartOfSpeechEnum::NOUN()],
			[RelationPointerTypeEnum::PERTAINYM(),                      '\\', PartOfSpeechEnum::ADJECTIVE()],
		];
	}

	/**
	 * @expectedException \UnexpectedValueException
	 */
	public function testMapRelationPointerTypeUnknown(): void
	{
		$mapper = new SynsetDataMapper();
		$mapper->mapRelationPointerType('not_exist', PartOfSpeechEnum::NOUN());
	}


	/**
	 * @dataProvider dpTestMapSynsetAdjectivesCategory
	 */
	public function testMapSynsetAdjectivesCategory(SynsetAdjectivesCategoryEnum $expectedSynsetCategory, int $data): void
	{
		$mapper = new SynsetDataMapper();
		static::assertEnum($expectedSynsetCategory, $mapper->mapSynsetAdjectivesCategory($data));
	}

	public function dpTestMapSynsetAdjectivesCategory(): array
	{
		return [
			[SynsetAdjectivesCategoryEnum::ALL(), 0],
			[SynsetAdjectivesCategoryEnum::RELATIONAL(), 1],
			[SynsetAdjectivesCategoryEnum::PARTICIPIAL(), 44],
		];
	}

	/**
	 * @expectedException \UnexpectedValueException
	 */
	public function testMapSynsetAdjectivesCategoryUnknown(): void
	{
		$mapper = new SynsetDataMapper();
		$mapper->mapSynsetAdjectivesCategory(1000);
	}


	/**
	 * @dataProvider dpTestMapSynsetAdverbsCategory
	 */
	public function testMapSynsetAdverbsCategory(SynsetAdverbsCategoryEnum $expectedSynsetCategory, int $data): void
	{
		$mapper = new SynsetDataMapper();
		static::assertEnum($expectedSynsetCategory, $mapper->mapSynsetAdverbsCategory($data));
	}

	public function dpTestMapSynsetAdverbsCategory(): array
	{
		return [
			[SynsetAdverbsCategoryEnum::ALL(), 2],
		];
	}

	/**
	 * @expectedException \UnexpectedValueException
	 */
	public function testMapSynsetAdverbsCategoryUnknown(): void
	{
		$mapper = new SynsetDataMapper();
		$mapper->mapSynsetAdverbsCategory(1000);
	}


	/**
	 * @dataProvider dpTestMapSynsetNounsCategory
	 */
	public function testMapSynsetNounsCategory(SynsetNounsCategoryEnum $expectedSynsetCategory, int $data): void
	{
		$mapper = new SynsetDataMapper();
		static::assertEnum($expectedSynsetCategory, $mapper->mapSynsetNounsCategory($data));
	}

	public function dpTestMapSynsetNounsCategory(): array
	{
		return [
			[SynsetNounsCategoryEnum::TOPS(),           3],
			[SynsetNounsCategoryEnum::ACT(),            4],
			[SynsetNounsCategoryEnum::ANIMAL(),         5],
			[SynsetNounsCategoryEnum::ARTIFACT(),       6],
			[SynsetNounsCategoryEnum::ATTRIBUTE(),      7],
			[SynsetNounsCategoryEnum::BODY(),           8],
			[SynsetNounsCategoryEnum::COGNITION(),      9],
			[SynsetNounsCategoryEnum::COMMUNICATION(), 10],
			[SynsetNounsCategoryEnum::EVENT(),         11],
			[SynsetNounsCategoryEnum::FEELING(),       12],
			[SynsetNounsCategoryEnum::FOOD(),          13],
			[SynsetNounsCategoryEnum::GROUP(),         14],
			[SynsetNounsCategoryEnum::LOCATION(),      15],
			[SynsetNounsCategoryEnum::MOTIVE(),        16],
			[SynsetNounsCategoryEnum::OBJECT(),        17],
			[SynsetNounsCategoryEnum::PERSON(),        18],
			[SynsetNounsCategoryEnum::PHENOMENON(),    19],
			[SynsetNounsCategoryEnum::PLANT(),         20],
			[SynsetNounsCategoryEnum::POSSESSION(),    21],
			[SynsetNounsCategoryEnum::PROCESS(),       22],
			[SynsetNounsCategoryEnum::QUANTITY(),      23],
			[SynsetNounsCategoryEnum::RELATION(),      24],
			[SynsetNounsCategoryEnum::SHAPE(),         25],
			[SynsetNounsCategoryEnum::STATE(),         26],
			[SynsetNounsCategoryEnum::SUBSTANCE(),     27],
			[SynsetNounsCategoryEnum::TIME(),          28],
		];
	}

	/**
	 * @expectedException \UnexpectedValueException
	 */
	public function testMapSynsetNounsCategoryUnknown(): void
	{
		$mapper = new SynsetDataMapper();
		$mapper->mapSynsetNounsCategory(1000);
	}


	/**
	 * @dataProvider dpTestMapSynsetVerbsCategory
	 */
	public function testMapSynsetVerbsCategory(SynsetVerbsCategoryEnum $expectedSynsetCategory, int $data): void
	{
		$mapper = new SynsetDataMapper();
		static::assertEnum($expectedSynsetCategory, $mapper->mapSynsetVerbsCategory($data));
	}

	public function dpTestMapSynsetVerbsCategory(): array
	{
		return [
			[SynsetVerbsCategoryEnum::BODY(),           29],
			[SynsetVerbsCategoryEnum::CHANGE(),         30],
			[SynsetVerbsCategoryEnum::COGNITION(),      31],
			[SynsetVerbsCategoryEnum::COMMUNICATION(),  32],
			[SynsetVerbsCategoryEnum::COMPETITION(),    33],
			[SynsetVerbsCategoryEnum::CONSUMPTION(),    34],
			[SynsetVerbsCategoryEnum::CONTACT(),        35],
			[SynsetVerbsCategoryEnum::CREATION(),       36],
			[SynsetVerbsCategoryEnum::EMOTION(),        37],
			[SynsetVerbsCategoryEnum::MOTION(),         38],
			[SynsetVerbsCategoryEnum::PERCEPTION(),     39],
			[SynsetVerbsCategoryEnum::POSSESSION(),     40],
			[SynsetVerbsCategoryEnum::SOCIAL(),         41],
			[SynsetVerbsCategoryEnum::STATIVE(),        42],
			[SynsetVerbsCategoryEnum::WEATHER(),        43],
		];
	}

	/**
	 * @expectedException \UnexpectedValueException
	 */
	public function testMapSynsetVerbsCategoryUnknown(): void
	{
		$mapper = new SynsetDataMapper();
		$mapper->mapSynsetVerbsCategory(1000);
	}
}
