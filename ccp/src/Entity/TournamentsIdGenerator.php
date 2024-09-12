<?php
namespace Poker\Ccp\Entity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;
use Poker\Ccp\Model\Constant;
class TournamentsIdGenerator extends AbstractIdGenerator
{
  public function generateId(EntityManagerInterface $em, $entity)
  {
    return $em->createQueryBuilder()
              ->select(select: "MAX(t.tournamentId) + 1")
              ->from(from: Constant::ENTITY_TOURNAMENTS, alias: "t")
              ->getQuery()->getSingleScalarResult();
  }
}