<?php
namespace Poker\Ccp\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use PDO;
class PayoutsRepository extends BaseRepository {
    public function getById(?int $payoutId) {
        $qb = $this->createQueryBuilder(alias: "p")
                   ->addSelect(select: "s")
                   ->innerJoin(join: "p.structures", alias: "s");
        if (isset($payoutId)) {
            $qb = $qb->where("p.payoutId = :payout");
            $qb->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "payout", value: $payoutId))));
        }
        return $qb->getQuery()->getResult();
    }

    public function getMaxId() {
        return $this->createQueryBuilder(alias: "p")
                    ->select(select: "MAX(p.payoutId)")
                    ->getQuery()->getSingleResult();
    }

    public function getTableOutput(?int $payoutId, bool $indexed) {
        $sql =
            "SELECT p.payout_id AS id, p.payout_name AS name, p.payout_min_players AS 'min players', p.payout_max_players AS 'max players', s.structure_place AS place, s.structure_percentage AS percentage " .
            "FROM poker_payouts p INNER JOIN poker_structures s ON p.payout_id = s.payout_id ";
        if (isset($payoutId)) {
            $sql .= "WHERE p.payout_id = :payoutId";
        }
        $statement = $this->getEntityManager()->getConnection()->prepare(sql: $sql);
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