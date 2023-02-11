<?php

declare(strict_types=1);

namespace AL\PhpWndb\Storage;

use AL\PhpWndb\Model\Index\SyntacticCategory;

interface Storage
{
    public function openDataStream(SyntacticCategory $syntacticCategory): Stream;

    public function openIndexStream(SyntacticCategory $syntacticCategory): Stream;
}
