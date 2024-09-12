<?php
declare(strict_types=1);
namespace Poker\Ccp\Entity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;
use Poker\Ccp\Model\Constant;
class LimitTypesIdGenerator extends AbstractIdGenerator
{
  public function generateId(EntityManagerInterface $em, $entity)
  {
    return $em->createQueryBuilder()
              ->select(select: "MAX(lt.limitTypeId) + 1")->from(from: Constant::ENTITY_LIMIT_TYPES, alias: "lt")
              ->getQuery()->getSingleScalarResult();
  }
}