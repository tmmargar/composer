<?php
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
require_once "vendor/autoload.php";
$config = ORMSetup::createAttributeMetadataConfiguration(paths: array("src", "src/Entity"), isDevMode: true);
$connection = DriverManager::getConnection(params: [ 'dbname' => 'chipch6_stats_generate_orm', 'user' => 'root', 'password' => 'toor', 'host' => 'localhost', 'driver' => 'pdo_mysql'], config: $config);
$entityManager = new EntityManager(conn: $connection, config: $config);