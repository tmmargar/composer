<?php
declare(strict_types=1);
namespace Poker\Ccp\Entity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;
use Poker\Ccp\Model\Constant;
class SeasonsIdGenerator extends AbstractIdGenerator
{
  public function generateId(EntityManagerInterface $em, $entity)
  {
      return $em->createQueryBuilder()
                ->select(select: "MAX(se.seasonId) + 1")
                ->from(from: Constant::ENTITY_SEASONS, alias: "se")
                ->getQuery()->getSingleScalarResult();
  }
}