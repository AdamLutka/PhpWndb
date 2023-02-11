<?php

declare(strict_types=1);

namespace AL\PhpWndb\Model\Data;

enum SynsetType
{
    case NOUN;
    case VERB;
    case ADJECTIVE;
    case ADJECTIVE_SATELLITE;
    case ADVERB;
}
