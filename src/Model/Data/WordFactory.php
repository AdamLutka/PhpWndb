<?php

declare(strict_types=1);

namespace AL\PhpWndb\Model\Data;

class WordFactory
{
    public function create(string $value): Word
    {
        return new Word($value);
    }
}
