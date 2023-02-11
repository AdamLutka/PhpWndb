<?php

declare(strict_types=1);

namespace AL\PhpWndb\Model\Data;

class Word
{
    public function __construct(
        protected readonly string $value,
    ) {
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
