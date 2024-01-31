<?php
namespace Poker\Ccp\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use PDO;
use Poker\Ccp\classes\model\Constant;
class FeesRepository extends BaseRepository {
    public function getForTournament(int $tournamentId, bool $greaterThanZero) {
//         case "feeCountByTournamentId":
//         "SELECT COUNT(*) AS cnt " .
//         "FROM poker_fees " .
//         "WHERE tournament_id = :tournamentId";
        $qb = $this->createQueryBuilder("f");
        $qb = $qb->where("f.tournaments = :tournamentId");
        if ($greaterThanZero) {
            $qb->andWhere("f.feeAmount > 0");
        }
        return $qb->setParameters(new ArrayCollection(array(new Parameter("tournamentId", $tournamentId))))->getQuery()->getResult();
    }

    public function deleteForSeason(int $seasonId) {
        return $this->getEntityManager()->getConnection()->executeStatement("DELETE FROM poker_fees WHERE season_id = ?", [$seasonId], [PDO::PARAM_INT]);
    }

    public function deleteForSeasonAndPlayer(int $seasonId, int $playerId) {
        return $this->getEntityManager()->getConnection()->executeStatement("DELETE FROM poker_fees WHERE season_id = ? AND player_id = ? AND fee_amount = 0", [$seasonId, $playerId], [PDO::PARAM_INT, PDO::PARAM_INT]);
    }

    public function getAmountForTournament(int $tournamentId) {
        $sql = "SELECT player_id, fee_amount FROM poker_fees WHERE tournament_id = :tournamentId";
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->bindValue("tournamentId", $tournamentId, PDO::PARAM_INT);
        return $statement->executeQuery()->fetchAllAssociative();
    }

    public function insertForSeasonAndTournament(int $seasonId, int $tournamentId) {
        $sql = "INSERT INTO poker_fees(season_id, player_id, tournament_id, fee_amount) SELECT ?, p.player_id, ?, 0 FROM poker_players p WHERE p.player_active_flag = " . Constant::FLAG_YES_DATABASE;
        return $this->getEntityManager()->getConnection()->executeStatement($sql, [$seasonId, $tournamentId], [PDO::PARAM_INT, PDO::PARAM_INT]);
    }

}