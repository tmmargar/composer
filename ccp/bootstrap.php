<?php
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
require_once "vendor/autoload.php";
function getEntityManager() : EntityManager
{
    $entityManager = null;
    if ($entityManager === null)
    {
        $config = ORMSetup::createAttributeMetadataConfiguration(paths: array("src", "src/Entity"), isDevMode: true);
        $config->addCustomStringFunction('DATEADD', 'DoctrineExtensions\Query\MySql\DateAdd');
        $config->addCustomStringFunction('IFNULL', 'DoctrineExtensions\Query\MySql\IfNull');
        $config->addCustomStringFunction('ROUND', 'DoctrineExtensions\Query\MySql\Round');
        $config->addCustomStringFunction('YEAR', 'DoctrineExtensions\Query\MySql\Year');
        $connection = DriverManager::getConnection(params: [ 'dbname' => 'chipch5_stats_orm', 'user' => 'root', 'password' => 'toor', 'host' => 'localhost', 'driver' => 'pdo_mysql'], config: $config);
        $entityManager = new EntityManager(conn: $connection, config: $config);
    }
    return $entityManager;
}