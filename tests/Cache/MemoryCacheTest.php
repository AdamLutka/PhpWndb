<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests\Cache;

use AL\PhpWndb\Cache\MemoryCache;
use AL\PhpWndb\Tests\BaseTestAbstract;

class MemoryCacheTest extends BaseTestAbstract
{
    public function testCache(): void
    {
        $cache = new MemoryCache(3);
        $cache->set('1', 'a');
        $cache->set('2', 'b');
        $cache->set('3', 'c');
        $cache->set('4', 'd');

        static::assertSame(123, $cache->get('1', 123));
        static::assertSame('b', $cache->get('2'));
        static::assertSame('c', $cache->get('3'));
        static::assertSame('d', $cache->get('4'));
    }
}
