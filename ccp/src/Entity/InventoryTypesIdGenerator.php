<?php
declare(strict_types=1);
namespace Poker\Ccp\Entity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;
use Poker\Ccp\Model\Constant;

class InventoryTypesIdGenerator extends AbstractIdGenerator
{
  public function generateId(EntityManagerInterface $em, $entity)
  {
    return $em->createQueryBuilder()
               ->select(select: "IFNULL(MAX(it.inventoryTypeId), 0) + 1")->from(from: Constant::ENTITY_INVENTORY_TYPES, alias: "it")
               ->getQuery()->getSingleScalarResult();
  }
}