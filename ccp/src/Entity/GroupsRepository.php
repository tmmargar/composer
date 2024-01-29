<?php
namespace Poker\Ccp\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use PDO;
class GroupsRepository extends BaseRepository {
    public function getById(?int $groupId) {
//         case "groupSelectAll":
//         case "groupSelectOneById":
//         case "groupSelectNameList":
        $qb = $this->createQueryBuilder("g");
        if (isset($groupId)) {
            $qb = $qb->where("g.groupId = :groupId");
            $qb->setParameters(new ArrayCollection(array(new Parameter("groupId", $groupId))));
        }
        return $qb->getQuery()->getResult();
    }

    public function getTableOutput(?int $groupId, bool $indexed) {
        $sql =
            "SELECT group_id AS id, group_name AS name " .
            "FROM poker_groups ";
        if (isset($groupId)) {
            $sql .= "WHERE group_id = :groupId";
        }
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        if (isset($groupId)) {
            $statement->bindValue("groupId", $groupId, PDO::PARAM_INT);
        }
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }
}