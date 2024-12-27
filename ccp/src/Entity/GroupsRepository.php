<?php
namespace Poker\Ccp\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use PDO;
class GroupsRepository extends BaseRepository {
    public function getById(?int $groupId) {
        $qb = $this->createQueryBuilder(alias: "g");
        if (isset($groupId)) {
            $qb = $qb->where(predicates: "g.groupId = :groupId");
            $qb->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "groupId", value: $groupId))));
        }
        return $qb->getQuery()->getResult();
    }

    public function getByName(string $groupName) {
        $qb = $this->createQueryBuilder(alias: "g");
        if (isset($groupName)) {
            $qb = $qb->where(predicates: "g.groupName = :groupName");
            $qb->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "groupName", value: $groupName))));
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
        $statement = $this->getEntityManager()->getConnection()->prepare(sql: $sql);
        if (isset($groupId)) {
            $statement->bindValue(param: "groupId", value: $groupId, type: PDO::PARAM_INT);
        }
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }
}