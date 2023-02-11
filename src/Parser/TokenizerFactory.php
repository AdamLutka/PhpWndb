<?php

declare(strict_types=1);

namespace AL\PhpWndb\Parser;

use AL\PhpWndb\Storage\Stream;

class TokenizerFactory
{
    public function create(Stream $stream): Tokenizer
    {
        return new Tokenizer($stream);
    }
}
