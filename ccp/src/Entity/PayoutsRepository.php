<?php
namespace Poker\Ccp\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use PDO;
class PayoutsRepository extends BaseRepository {
    public function getById(?int $payoutId) {
//         case "payoutSelectAll":
//         case "payoutSelectOneById":
//         case "payoutSelectNameList":
        $qb = $this->createQueryBuilder("p")
                   ->addSelect("s")
                   ->innerJoin("p.structures", "s");
        if (isset($payoutId)) {
            $qb = $qb->where("p.payoutId = :payout");
            $qb->setParameters(new ArrayCollection(array(new Parameter("payout", $payoutId))));
        }
        return $qb->getQuery()->getResult();
    }

    public function getMaxId() {
//      case "payoutSelectMaxId":
        $qb = $this->createQueryBuilder("p");
        return $qb->select("MAX(p.payoutId)")->getQuery()->getSingleResult();
    }

    public function getTableOutput(?int $payoutId, bool $indexed) {
        $sql =
            "SELECT p.payout_id AS id, p.payout_name AS name, p.payout_min_players AS 'min players', p.payout_max_players AS 'max players', s.structure_place AS place, s.structure_percentage AS percentage " .
            "FROM poker_payouts p INNER JOIN poker_structures s ON p.payout_id = s.payout_id ";
        if (isset($payoutId)) {
            $sql .= "WHERE p.payout_id = :payoutId";
        }
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        if (isset($payoutId)) {
            $statement->bindValue("payoutId", $payoutId, PDO::PARAM_INT);
        }
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }
}