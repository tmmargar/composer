<?php
namespace Poker\Ccp\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use PDO;
class StructuresRepository extends BaseRepository {

    public function getById(?int $payoutId) {
        $qb = $this->createQueryBuilder(alias: "s");
        if (isset($payoutId)) {
            $qb = $qb->where(predicates: "s.payouts = :payoutId")
                     ->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "payoutId", value: $payoutId))));
        }
        return $qb->getQuery()->getResult();
    }

    public function deleteForPayout(int $payoutId) {
        return $this->getEntityManager()->getConnection()->executeStatement(sql: "DELETE FROM poker_structures WHERE payout_id = ?", params: [$payoutId], types: [PDO::PARAM_INT]);
    }
}