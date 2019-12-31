<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests\Model\Relations;

use AL\PhpWndb\Model\Relations\Relations;
use AL\PhpWndb\Model\Relations\RelationPointer;
use AL\PhpWndb\Model\Relations\RelationPointerTypeEnum;
use AL\PhpWndb\PartOfSpeechEnum;
use AL\PhpWndb\Tests\BaseTestAbstract;

class RelationsTest extends BaseTestAbstract
{
	/** @var Relations */
	private $relations;

	/** @var RelationPointer */
	private $antonymPointer;

	/** @var RelationPointer */
	private $hypernymPointer1;

	/** @var RelationPointer */
	private $hypernymPointer2;


	public function setUp(): void
	{
		parent::setUp();

		$this->relations = new Relations();
		$this->antonymPointer = new RelationPointer(
			RelationPointerTypeEnum::ANTONYM(),
			PartOfSpeechEnum::NOUN(),
			1,
			1
		);
		$this->hypernymPointer1 = new RelationPointer(
			RelationPointerTypeEnum::HYPERNYM(),
			PartOfSpeechEnum::VERB(),
			2,
			1
		);
		$this->hypernymPointer2 = new RelationPointer(
			RelationPointerTypeEnum::HYPERNYM(),
			PartOfSpeechEnum::VERB(),
			2,
			2
		);

		$this->relations->addRelationPointer($this->antonymPointer);
		$this->relations->addRelationPointers([
			$this->hypernymPointer1,
			$this->hypernymPointer2,
		]);
	}

	public function testGetRelationPointersOfType(): void
	{
		static::assertSame(
			[$this->hypernymPointer1, $this->hypernymPointer2],
			$this->relations->getRelationPointersOfType(RelationPointerTypeEnum::HYPERNYM())
		);
		static::assertSame(
			[$this->antonymPointer],
			$this->relations->getRelationPointersOfType(RelationPointerTypeEnum::ANTONYM())
		);
		static::assertEmpty(
			$this->relations->getRelationPointersOfType(RelationPointerTypeEnum::ALSO_SEE())
		);
	}

	public function testGetAllRelationPointers(): void
	{
		static::assertSame(
			[$this->antonymPointer, $this->hypernymPointer1, $this->hypernymPointer2],
			$this->relations->getAllRelationPointers()
		);
	}
}
