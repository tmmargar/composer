<?php
namespace Poker\Ccp\Entity;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use PDO;
use Poker\Ccp\Model\Constant;
use Poker\Ccp\Utility\DateTimeUtility;
class SeasonsRepository extends BaseRepository {
    public function getActives() {
        return $this->createQueryBuilder(alias: "s")
                    ->where(predicates: "s.seasonActiveFlag = " . Constant::FLAG_YES_DATABASE)
                    ->getQuery()->getSingleResult();
    }

    public function getById(?int $seasonId) {
        $qb = $this->createQueryBuilder(alias: "s");
        if (isset($seasonId)) {
            $qb = $qb->where(predicates: "s.seasonId = :seasonId");
            $qb->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "seasonId", value: $seasonId))));
        }
        return $qb->getQuery()->getResult();
    }

    public function getByTournamentIdAndSpecialTypeDescription(?int $tournamentId, ?string $specialTypeDescription) {
        $sql =
            "SELECT s.season_id, s.season_description, s.season_start_date, s.season_end_date, s.season_championship_qualification_count, s.season_final_table_players, s.season_final_table_bonus_points, s.season_fee, s.season_active_flag " .
            "FROM poker_seasons s INNER JOIN poker_tournaments t " .
            "LEFT JOIN poker_special_types st ON t.special_type_id = st.special_type_id " .
            "WHERE t.tournament_date BETWEEN s.season_start_date AND s.season_end_date " .
            "AND t.tournament_id = :tournamentId " .
            "AND st.special_type_description = :typeDescription";
        $statement = $this->getEntityManager()->getConnection()->prepare(sql: $sql);
        $statement->bindValue(param: "tournamentId", value: $tournamentId);
        $statement->bindValue(param: "typeDescription", value: $specialTypeDescription);
        return $statement->executeQuery()->fetchAllAssociative();
    }

    public function getMaxId() {
        return $this->createQueryBuilder(alias: "s")
                    ->select(select: "MAX(s.seasonId)")->getQuery()->getSingleResult();
    }

    public function getTableOutput(?int $seasonId, bool $indexed) {
        $sql =
            "SELECT season_id AS id, season_description AS description, season_start_date AS 'start date', season_end_date AS 'end date', season_championship_qualification_count AS '# to qualify', season_final_table_players AS 'final table players', season_final_table_bonus_points AS 'final table bonus pts', season_fee AS fee, season_active_flag AS active " .
            "FROM poker_seasons";
        if (isset($seasonId)) {
            $sql .= " WHERE season_id = :seasonId";
        }
        $statement = $this->getEntityManager()->getConnection()->prepare(sql: $sql);
        if (isset($seasonId)) {
            $statement->bindValue(param: "seasonId", value: $seasonId, type: PDO::PARAM_INT);
        }
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }

    public function getDateCheckCount(DateTime $date1, DateTime $date2, int $seasonId) {
        $sql =
            "SELECT COUNT(*) AS cnt " .
            "FROM poker_seasons " .
            "WHERE (:date1 BETWEEN season_start_date AND season_end_date OR :date2 BETWEEN season_start_date AND season_end_date)";
        if (isset($seasonId)) {
            $sql .= " AND season_id <> :seasonId";
        }
        $statement = $this->getEntityManager()->getConnection()->prepare(sql: $sql);
        $date1Formatted = DateTimeUtility::formatDatabaseDate(value: $date1);
        $date2Formatted = DateTimeUtility::formatDatabaseDate(value: $date2);
        $statement->bindValue(param: "date1", value: $date1Formatted, type: PDO::PARAM_STR);
        $statement->bindValue(param: "date2", value: $date2Formatted, type: PDO::PARAM_STR);
        if (isset($seasonId)) {
            $statement->bindValue(param: "seasonId", value: $seasonId, type: PDO::PARAM_INT);
        }
        return $statement->executeQuery()->fetchAllNumeric();
    }

    public function getForTournament(int $tournamentId) {
        $sql =
            "SELECT s.season_id " .
            "FROM poker_seasons s INNER JOIN poker_tournaments t ON t.tournament_date BETWEEN s.season_start_date AND s.season_end_date AND t.tournament_id = :tournamentId";
        $statement = $this->getEntityManager()->getConnection()->prepare(sql: $sql);
        $statement->bindValue(param: "tournamentId", value: $tournamentId, type: PDO::PARAM_INT);
        return $statement->executeQuery()->fetchAllAssociative();
    }
}