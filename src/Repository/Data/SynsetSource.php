<?php

declare(strict_types=1);

namespace AL\PhpWndb\Repository\Data;

use AL\PhpWndb\Model\Data\Synset;
use AL\PhpWndb\Model\Index\SyntacticCategory;
use AL\PhpWndb\Parser\Exception\ParseException;
use AL\PhpWndb\Parser\SynsetParser;
use AL\PhpWndb\Storage\Stream;

class SynsetSource
{
    public function __construct(
        protected readonly SyntacticCategory $syntacticCategory,
        protected readonly Stream $stream,
        protected readonly SynsetParser $parser,
    ) {
    }

    /**
     * @throw ParseException
     */
    public function getSynset(int $synsetOffset): Synset
    {
        $this->stream->seek($synsetOffset);

        try {
            return $this->parser->parseSynset($this->stream);
        } catch (ParseException $exception) {
            $category = $this->syntacticCategory->name;
            throw new ParseException(
                message: "Parse synset($category) `{$synsetOffset}` failed: " . $exception->getMessage(),
                previous: $exception,
            );
        }
    }
}
