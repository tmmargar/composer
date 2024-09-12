<?php
use Doctrine\ORM\Tools\EntityGenerator;
require_once "bootstrap.php";
$entityManager = getEntityManager();
$entityManager->getConfiguration()->setMetadataDriverImpl(new \Doctrine\ORM\Mapping\Driver\DatabaseDriver(schemaManager: $entityManager->getConnection()->getSchemaManager()));
$cmf = new \Doctrine\ORM\Tools\DisconnectedClassMetadataFactory();
$cmf->setEntityManager(em: $entityManager);
$metadata = $cmf->getAllMetadata();
/*$cme = new \Doctrine\ORM\Tools\Export\ClassMetadataExporter();
$exporter = $cme->getExporter('annotation', __DIR__.'/src/classes/entity');
$exporter->
$exporter->setMetadata($metadata);
$exporter->export();*/
$generator = new EntityGenerator();
$generator->setUpdateEntityIfExists(bool: true);
$generator->setGenerateStubMethods(bool: true);
$generator->setGenerateAnnotations(bool: true);
$generator->generate(metadatas: $metadata, outputDirectory: __DIR__ . '/src/EntityTest');