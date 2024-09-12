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
    return $em->createQueryBuilder()
              ->select(select: "MAX(n.notificationId) + 1")
              ->from(from: Constant::ENTITY_NOTIFICATIONS, alias: "n")
              ->getQuery()->getSingleScalarResult();
  }
}