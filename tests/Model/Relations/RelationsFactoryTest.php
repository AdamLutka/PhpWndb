<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests\Model\Relations;

use AL\PhpWndb\Model\Relations\Relations;
use AL\PhpWndb\Model\Relations\RelationPointerInterface;
use AL\PhpWndb\Model\Relations\RelationsFactory;
use AL\PhpWndb\Tests\BaseTestAbstract;

class RelationsFactoryTest extends BaseTestAbstract
{
	public function testCreateRelation(): void
	{
		$relationPointers = [$this->createMock(RelationPointerInterface::class)];

		$factory = new RelationsFactory();
		$relations = $factory->createRelations($relationPointers);

		static::assertInstanceOf(Relations::class, $relations);
		static::assertSame($relationPointers, $relations->getAllRelationPointers());
	}
}
