<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
require_once "bootstrapGenerate.php";
// ConsoleRunner::run(new SingleManagerProvider($entityManager));
$tool = new \Doctrine\ORM\Tools\SchemaTool($entityManager);
$classes = array(
    $entityManager->getClassMetadata('Poker\Ccp\Entity\Fees'),
    $entityManager->getClassMetadata('Poker\Ccp\Entity\GameTypes'),
    $entityManager->getClassMetadata('Poker\Ccp\Entity\GroupPayouts'),
    $entityManager->getClassMetadata('Poker\Ccp\Entity\Groups'),
    $entityManager->getClassMetadata('Poker\Ccp\Entity\LimitTypes'),
    $entityManager->getClassMetadata('Poker\Ccp\Entity\Locations'),
    $entityManager->getClassMetadata('Poker\Ccp\Entity\Notifications'),
    $entityManager->getClassMetadata('Poker\Ccp\Entity\Payouts'),
    $entityManager->getClassMetadata('Poker\Ccp\Entity\Players'),
    $entityManager->getClassMetadata('Poker\Ccp\Entity\Results'),
    $entityManager->getClassMetadata('Poker\Ccp\Entity\Seasons'),
    $entityManager->getClassMetadata('Poker\Ccp\Entity\SpecialTypes'),
    $entityManager->getClassMetadata('Poker\Ccp\Entity\StatusCodes'),
    $entityManager->getClassMetadata('Poker\Ccp\Entity\Structures'),
    $entityManager->getClassMetadata('Poker\Ccp\Entity\TournamentAbsences'),
    $entityManager->getClassMetadata('Poker\Ccp\Entity\Tournaments')
    $entityManager->getClassMetadata('Poker\Ccp\Entity\blobtest')
);
$tool->dropSchema($classes);
$tool->createSchema($classes);