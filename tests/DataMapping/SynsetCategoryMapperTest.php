<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests\DataMapping;

use AL\PhpWndb\DataMapping\SynsetCategoryMapper;
use AL\PhpWndb\Model\Synsets\Adjectives\SynsetAdjectivesCategoryEnum;
use AL\PhpWndb\Model\Synsets\Adverbs\SynsetAdverbsCategoryEnum;
use AL\PhpWndb\Model\Synsets\Nouns\SynsetNounsCategoryEnum;
use AL\PhpWndb\Model\Synsets\Verbs\SynsetVerbsCategoryEnum;
use AL\PhpWndb\Tests\BaseTestAbstract;

class SynsetCategoryMapperTest extends BaseTestAbstract
{
	/**
	 * @dataProvider dpTestMapSynsetAdjectivesCategory
	 */
	public function testMapSynsetAdjectivesCategory(SynsetAdjectivesCategoryEnum $expectedSynsetCategory, int $data): void
	{
		$mapper = new SynsetCategoryMapper();
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
		$mapper = new SynsetCategoryMapper();
		$mapper->mapSynsetAdjectivesCategory(1000);
	}


	/**
	 * @dataProvider dpTestMapSynsetAdverbsCategory
	 */
	public function testMapSynsetAdverbsCategory(SynsetAdverbsCategoryEnum $expectedSynsetCategory, int $data): void
	{
		$mapper = new SynsetCategoryMapper();
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
		$mapper = new SynsetCategoryMapper();
		$mapper->mapSynsetAdverbsCategory(1000);
	}


	/**
	 * @dataProvider dpTestMapSynsetNounsCategory
	 */
	public function testMapSynsetNounsCategory(SynsetNounsCategoryEnum $expectedSynsetCategory, int $data): void
	{
		$mapper = new SynsetCategoryMapper();
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
		$mapper = new SynsetCategoryMapper();
		$mapper->mapSynsetNounsCategory(1000);
	}


	/**
	 * @dataProvider dpTestMapSynsetVerbsCategory
	 */
	public function testMapSynsetVerbsCategory(SynsetVerbsCategoryEnum $expectedSynsetCategory, int $data): void
	{
		$mapper = new SynsetCategoryMapper();
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
		$mapper = new SynsetCategoryMapper();
		$mapper->mapSynsetVerbsCategory(1000);
	}
}
