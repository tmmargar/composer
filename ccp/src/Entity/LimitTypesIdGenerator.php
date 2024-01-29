<?php
declare(strict_types=1);
namespace Poker\Ccp\Entity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;
class LimitTypesIdGenerator extends AbstractIdGenerator
{
  public function generateId(EntityManagerInterface $em, $entity)
  {
    $queryBuilder = $em->createQueryBuilder();
    $queryBuilder->select("MAX(lt.limitTypeId) + 1")->from("Poker\Ccp\Entity\LimitTypes", "lt");
    $query = $queryBuilder->getQuery();
    $result = $query->getSingleScalarResult();
    return $result;
  }
}