<?php
namespace Poker\Ccp\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use PDO;
class StructuresRepository extends BaseRepository {

    public function getById(?int $payoutId) {
        $qb = $this->createQueryBuilder("s");
        if (isset($payoutId)) {
            $qb = $qb->where("s.payouts = :payoutId");
            $qb->setParameters(new ArrayCollection(array(new Parameter("payoutId", $payoutId))));
        }
        return $qb->getQuery()->getResult();
    }

    public function deleteForPayout(int $payoutId) {
        return $this->getEntityManager()->getConnection()->executeStatement("DELETE FROM poker_structures WHERE payout_id = ?", [$payoutId], [PDO::PARAM_INT]);
    }
}