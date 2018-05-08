<?php
declare(strict_types=1);

use AL\PhpWndb\DiContainerFactory;
use AL\PhpWndb\PartOfSpeechEnum;
use AL\PhpWndb\Repositories\SynsetMultiRepositoryInterface;

require_once(__DIR__ . '/../vendor/autoload.php');

$containerFactory = new DiContainerFactory();
$container = $containerFactory->createContainer();

/** @var SynsetMultiRepositoryInterface */
$repository = $container->get(SynsetMultiRepositoryInterface::class);

$synset = $repository->getSynsetByPartOfSpeech(PartOfSpeechEnum::VERB(), 1622744);
$words = $synset->getWords();

echo 'Synset offset: ' . $synset->getSynsetOffset() . "\n";
echo 'Gloss: ' . $synset->getGloss() . "\n";
echo "Words:\n";

foreach ($words as $word) {
	echo "  " . $word->getLemma() . "\n";
}
