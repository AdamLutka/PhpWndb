<?php

declare(strict_types=1);

namespace AL\PhpWndb\Search\Index;

class LemmaFactory
{
    public function create(string $searchTerm): string
    {
        // TODO
        return \strtolower(\preg_replace('~[^a-z0-9]~i', '_', $searchTerm) ?? '');
    }
}
