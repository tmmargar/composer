<?php
declare(strict_types=1);
namespace Poker\Ccp\Entity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;
use Poker\Ccp\Model\Constant;

class NotificationsIdGenerator extends AbstractIdGenerator
{
  public function generateId(EntityManagerInterface $em, $entity)
  {
    $queryBuilder = $em->createQueryBuilder();
    $queryBuilder->select("MAX(n.notificationId) + 1")->from(Constant::ENTITY_NOTIFICATIONS, "n");
    $query = $queryBuilder->getQuery();
    $result = $query->getSingleScalarResult();
    return $result;
  }
}