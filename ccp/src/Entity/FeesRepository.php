<?php
namespace Poker\Ccp\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use PDO;
use Poker\Ccp\Model\Constant;
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

    public function getBySeason(bool $indexed) {
        //         case "feeSelectBySeason":
        $sql =
            "SELECT s.season_id, s.season_description AS description, s.season_start_date AS 'start date', s.season_end_date AS 'end date', SUM(f.fee_amount) AS amount " .
            "FROM poker_fees f INNER JOIN poker_seasons s ON f.season_id = s.season_id " .
            "LEFT JOIN (SELECT DISTINCT se.season_id, l.location_id, l.location_name, l.player_id, p.player_first_name, p.player_last_name " .
            "           FROM poker_tournaments t INNER JOIN poker_seasons se ON t.tournament_date BETWEEN se.season_start_date AND se.season_end_date " .
            "           INNER JOIN poker_locations l ON t.location_id = l.location_id " .
            "           INNER JOIN poker_players p ON l.player_id = p.player_id " .
            "           GROUP BY se.season_id, l.location_id) fh ON f.season_id = fh.season_id AND f.player_id = fh.player_id " .
            "WHERE fh.player_id IS NULL " .
            "GROUP BY s.season_id";
        //             $qb = $this->createQueryBuilder("s");
        //             return $qb->select("s, SUM(f.feeAmount) AS amount")
        //                       ->innerJoin("s.fees", "f")
        //                       ->groupBy("s.seasonId")
        //                       ->getQuery()->getResult();
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }
}