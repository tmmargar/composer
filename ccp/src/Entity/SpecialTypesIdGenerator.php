<?php
namespace Poker\Ccp\Entity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;
use Poker\Ccp\Model\Constant;
class SpecialTypesIdGenerator extends AbstractIdGenerator
{
  public function generateId(EntityManagerInterface $em, $entity)
  {
    return $em->createQueryBuilder()
              ->select(select: "MAX(st.specialTypeId) + 1")
              ->from(from: Constant::ENTITY_SPECIAL_TYPES, alias: "st")
              ->getQuery()->getSingleScalarResult();
  }
}