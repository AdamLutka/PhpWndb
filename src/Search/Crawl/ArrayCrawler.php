<?php

declare(strict_types=1);

namespace AL\PhpWndb\Search\Crawl;

use ArrayIterator;
use Exception;

/**
 * @template TValue of mixed
 * @extends  ArrayIterator<int, TValue>
 */
class ArrayCrawler extends ArrayIterator
{
    /**
     * @param TValue[] $array
     */
    public function __construct(array $array)
    {
        parent::__construct(\array_values($array));
    }

    /**
     * @return TValue
     */
    public function getFirst(): mixed
    {
        return $this[0] ?? throw new Exception('There is no first item.');
    }

    /**
     * @return TValue
     */
    public function getLast(): mixed
    {
        return $this[\count($this) - 1]  ?? throw new Exception('There is no last item.');
    }
}
