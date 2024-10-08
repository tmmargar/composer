<?php
namespace Poker\Ccp\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use PDO;
class GroupPayoutsRepository extends BaseRepository {
    public function getById(?int $groupId, ?int $payoutId) {
        $qb = $this->createQueryBuilder(alias: "gp");
        if (isset($groupId) && isset($payoutId)) {
            $qb = $qb->where(predicates: "gp.groups = :groupId")
                     ->andWhere("gp.payouts = :payoutId");
            $qb->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "groupId", value: $groupId), new Parameter(name: "payoutId", value: $payoutId))));
        }
        return $qb->getQuery()->getResult();
    }

    public function getTableOutput(?int $groupId, ?int $payoutId, bool $indexed) {
        $sql =
            "SELECT gp.group_id AS id, g.group_name AS 'group name', gp.payout_id AS 'payout id', p.payout_name AS 'payout name' " .
            "FROM poker_group_payouts gp INNER JOIN poker_groups g ON gp.group_id = g.group_id " .
            "INNER JOIN poker_payouts p ON gp.payout_id = p.payout_id ";
        if (isset($groupId) && isset($payoutId)) {
            $sql .=
                "WHERE gp.group_id = :groupId " .
                "AND gp.payout_id = :payoutId";
        }
        $statement = $this->getEntityManager()->getConnection()->prepare(sql: $sql);
        if (isset($groupId)) {
            $statement->bindValue(param: "groupId", value: $groupId, type: PDO::PARAM_INT);
        }
        if (isset($payoutId)) {
            $statement->bindValue(param: "payoutId", value: $payoutId, type: PDO::PARAM_INT);
        }
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }
}