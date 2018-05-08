<?php
declare(strict_types=1);

use AL\PhpWndb\DiContainerFactory;
use AL\PhpWndb\PartOfSpeechEnum;
use AL\PhpWndb\Repositories\WordIndexRepositoryInterface;

require_once(__DIR__ . '/../vendor/autoload.php');

$containerFactory = new DiContainerFactory();
$container = $containerFactory->createContainer();

/** @var WordIndexRepositoryInterface */
$repository = $container->get(WordIndexRepositoryInterface::class);

$index = $repository->findWordIndex('cat');
$synsetOffsets = $index->getSynsetOffsets();

echo 'Lemma: ' . $index->getLemma() . "\n";
echo 'Part of speech: ' . $index->getPartOfSpeech() . "\n";
echo "Synsets:\n";

foreach ($synsetOffsets as $synsetOffset) {
	echo "  " . $synsetOffset . "\n";
}
