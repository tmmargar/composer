<?php
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use DoctrineExtensions\Query\Mysql\DateAdd;
use DoctrineExtensions\Query\Mysql\IfNull;
use DoctrineExtensions\Query\Mysql\Round;
use DoctrineExtensions\Query\Mysql\Year;
use Poker\Ccp\Model\Constant;
require_once "vendor/autoload.php";
function getEntityManager() : EntityManager
{
    $entityManager = null;
    if ($entityManager === null)
    {
        $config = ORMSetup::createAttributeMetadataConfiguration(paths: array("src", "src/Entity"), isDevMode: true);
        $config->addCustomDateTimeFunction(name: 'DATEADD', className: DateAdd::class);
        $config->addCustomDateTimeFunction(name: 'IFNULL', className: IfNull::class);
        $config->addCustomDateTimeFunction(name: 'ROUND', className: Round::class);
        $config->addCustomDateTimeFunction(name: 'YEAR', className: Year::class);
        if ($_SERVER["SERVER_NAME"] == Constant::URL || $_SERVER["SERVER_NAME"] == Constant::URL_WWW) {
            $username = Constant::DATABASE_USER_NAME_SERVER;
            $password = Constant::DATABASE_PASSWORD_SERVER;
        } else {
            $username = Constant::DATABASE_USER_NAME_LOCAL;
            $password = Constant::DATABASE_PASSWORD_LOCAL;
        }
        $connection = DriverManager::getConnection(params: [ 'dbname' => Constant::DATABASE_NAME, 'user' => $username, 'password' => $password, 'host' => Constant::DATABASE_HOST_NAME, 'driver' => 'pdo_mysql'], config: $config);
        $entityManager = new EntityManager(conn: $connection, config: $config);
    }
    return $entityManager;
}