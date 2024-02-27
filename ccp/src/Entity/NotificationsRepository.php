<?php
namespace Poker\Ccp\Entity;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use PDO;
use Poker\Ccp\Utility\DateTimeUtility;
class NotificationsRepository extends BaseRepository {
    public function getByDate(DateTime $date) {
//      case "notifcationSelectAll":
//      " WHERE :tournamentDate BETWEEN notification_start_date AND notification_end_date ";
        $qb = $this->createQueryBuilder("n");
        $dateFormatted = DateTimeUtility::formatDatabaseDateTime(value: $date);
        if (isset($date)) {
            $qb = $qb->where(":notificationDate BETWEEN n.notificationStartDate AND n.notificationEndDate");
            $qb->setParameters(new ArrayCollection(array(new Parameter("notificationDate", $dateFormatted))));
        }
        return $qb->getQuery()->getResult();
    }

    public function getById(?int $notificationId) {
        //         case "notifcationSelectAll":
        //         case "notifcationSelectById":
        $qb = $this->createQueryBuilder("n");
        if (isset($notificationId)) {
            $qb = $qb->where("n.notificationId = :notificationId");
            $qb->setParameters(new ArrayCollection(array(new Parameter("notificationId", $notificationId))));
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
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        if (isset($notificationId)) {
            $statement->bindValue("notificationId", $notificationId, PDO::PARAM_INT);
        }
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }
}