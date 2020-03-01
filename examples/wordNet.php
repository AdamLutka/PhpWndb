<?php
declare(strict_types=1);

use AL\PhpWndb\DiContainerFactory;
use AL\PhpWndb\WordNet;

require_once(__DIR__ . '/../vendor/autoload.php');

$containerFactory = new DiContainerFactory();
$container = $containerFactory->createContainer();

/** @var WordNet */
$wordNet = $container->get(WordNet::class);

$synsets = $wordNet->searchSynsets('cat')->getAllSynsets();

foreach ($synsets as $synset) {
	echo "  " . $synset->getGloss() . "\n";
}
