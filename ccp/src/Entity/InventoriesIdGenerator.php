<?php
declare(strict_types=1);
namespace Poker\Ccp\Entity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;
use Poker\Ccp\Model\Constant;

class InventoriesIdGenerator extends AbstractIdGenerator
{
  public function generateId(EntityManagerInterface $em, $entity)
  {
    return $em->createQueryBuilder()
              ->select(select: "IFNULL(MAX(i.inventoryId), 0) + 1")->from(from: Constant::ENTITY_INVENTORIES, alias: "i")
              ->getQuery()->getSingleScalarResult();
  }
}