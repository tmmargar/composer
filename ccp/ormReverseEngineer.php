<?php
use Doctrine\ORM\Tools\EntityGenerator;
require_once "bootstrap.php";
$entityManager = getEntityManager();
$entityManager->getConfiguration()->setMetadataDriverImpl(
  new \Doctrine\ORM\Mapping\Driver\DatabaseDriver(
    $entityManager->getConnection()->getSchemaManager()
    )
  );

$cmf = new \Doctrine\ORM\Tools\DisconnectedClassMetadataFactory();
$cmf->setEntityManager($entityManager);
$metadata = $cmf->getAllMetadata();
/*$cme = new \Doctrine\ORM\Tools\Export\ClassMetadataExporter();
$exporter = $cme->getExporter('annotation', __DIR__.'/src/classes/entity');
$exporter->
$exporter->setMetadata($metadata);
$exporter->export();*/
$generator = new EntityGenerator();
$generator->setUpdateEntityIfExists(true);
$generator->setGenerateStubMethods(true);
$generator->setGenerateAnnotations(true);
$generator->generate($metadata, __DIR__ . '/src/EntityTest');