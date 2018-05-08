<?php
declare(strict_types=1);

namespace AL\PhpWndb\Repositories;

use AL\PhpWndb\Exceptions\UnknownSynsetOffsetException;
use AL\PhpWndb\PartOfSpeechEnum;
use AL\PhpWndb\Model\Synsets\SynsetInterface;
use UnexpectedValueException;

interface SynsetMultiRepositoryInterface extends SynsetRepositoryInterface
{
	/**
	 * @throws UnexpectedValueException
	 */
	public function findSynsetByPartOfSpeech(PartOfSpeechEnum $partOfSpeech, int $synsetOffset): ?SynsetInterface;

	/**
	 * @throws UnexpectedValueException
	 * @throws UnknownSynsetOffsetException
	 */
	public function getSynsetByPartOfSpeech(PartOfSpeechEnum $partOfSpeech, int $synsetOffset): SynsetInterface;
}
