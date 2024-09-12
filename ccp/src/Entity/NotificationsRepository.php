<?php
namespace Poker\Ccp\Entity;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use PDO;
use Poker\Ccp\Utility\DateTimeUtility;
class NotificationsRepository extends BaseRepository {
    public function getByDate(DateTime $date) {
        $qb = $this->createQueryBuilder(alias: "n");
        $dateFormatted = DateTimeUtility::formatDatabaseDateTime(value: $date);
        if (isset($date)) {
            $qb = $qb->where(predicates: ":notificationDate BETWEEN n.notificationStartDate AND n.notificationEndDate");
            $qb->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "notificationDate", value: $dateFormatted))));
        }
        return $qb->getQuery()->getResult();
    }

    public function getById(?int $notificationId) {
        $qb = $this->createQueryBuilder(alias: "n");
        if (isset($notificationId)) {
            $qb = $qb->where(predicates: "n.notificationId = :notificationId");
            $qb->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "notificationId", value: $notificationId))));
        }
        return $qb->getQuery()->getResult();
    }

    public function getTableOutput(?int $notificationId, bool $indexed) {
        $sql =
            "SELECT notification_id AS id, notification_description AS description, notification_start_date AS 'start date', notification_end_date AS 'end date' " .
            "FROM poker_notifications ";
        if (isset($notificationId)) {
            $sql .= "WHERE notification_id = :notificationId";
        }
        $statement = $this->getEntityManager()->getConnection()->prepare(sql: $sql);
        if (isset($notificationId)) {
            $statement->bindValue(param: "notificationId", value: $notificationId, type: PDO::PARAM_INT);
        }
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }
}