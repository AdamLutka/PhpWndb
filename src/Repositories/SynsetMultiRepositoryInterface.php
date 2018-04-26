<?php
declare(strict_types=1);

namespace AL\PhpWndb\Repositories;

use AL\PhpWndb\PartOfSpeechEnum;
use AL\PhpWndb\Model\Synsets\SynsetInterface;

interface SynsetMultiRepositoryInterface
{
	public function getSynsetByOffset(PartOfSpeechEnum $partOfSpeech, int $synsetOffset): SynsetInterface;

	public function dispose(SynsetInterface $synset): void;
}
