<?php
declare(strict_types=1);
namespace Poker\Ccp\Entity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;
use Poker\Ccp\Model\Constant;

class LocationsIdGenerator extends AbstractIdGenerator
{
  public function generateId(EntityManagerInterface $em, $entity)
  {
    return $em->createQueryBuilder()
              ->select(select: "MAX(l.locationId) + 1")->from(from: Constant::ENTITY_LOCATIONS, alias: "l")
              ->getQuery()->getSingleScalarResult();
  }
}