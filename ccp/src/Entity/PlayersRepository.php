<?php
namespace Poker\Ccp\Entity;
use DateInterval;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\Parameter;
use PDO;
use Poker\Ccp\Model\Constant;
use Poker\Ccp\Utility\DateTimeUtility;
class PlayersRepository extends BaseRepository {
    public function getActives() {
        return $this->createQueryBuilder("p")->where("p.playerActiveFlag = " . Constant::FLAG_YES_DATABASE)->getQuery()->getResult();
    }

    public function getByName(string $name) {
        $names = explode(" ", $name);
        return $this->createQueryBuilder("p")
                    ->addSelect("pa, pr")
                    ->leftJoin("p.playerApproval", "pa")
                    ->leftJoin("p.playerRejection", "pr")
                    ->where("p.playerFirstName = :firstName")
                    ->andWhere("p.playerLastName = :lastName")
                    ->setParameters(new ArrayCollection(array(new Parameter("firstName", $names[0]), new Parameter("lastName", $names[1]))))
                    ->getQuery()->getResult();
    }

    public function getByUsername(string $username) {
        return $this->createQueryBuilder("p")
                    ->addSelect("pa, pr")
                    ->leftJoin("p.playerApproval", "pa")
                    ->leftJoin("p.playerRejection", "pr")
                    ->where("p.playerUsername = :username")
                    ->setParameters(new ArrayCollection(array(new Parameter("username", $username))))
                    ->getQuery()->getResult();
    }

    public function getByEmail(string $email) {
        return $this->createQueryBuilder("p")
                    ->addSelect("pa, pr")
                    ->leftJoin("p.playerApproval", "pa")
                    ->leftJoin("p.playerRejection", "pr")
                    ->where("p.playerEmail = :email")
                    ->setParameters(new ArrayCollection(array(new Parameter("email", $email))))
                    ->getQuery()->getResult();
    }

    public function getByUsernameAndEmail(string $username, string $email) {
        return $this->createQueryBuilder("p")
                    ->addSelect("pa, pr")
                    ->leftJoin("p.playerApproval", "pa")
                    ->leftJoin("p.playerRejection", "pr")
                    ->where("p.playerUsername = :username")
                    ->andWhere("p.playerEmail = :email")
                    ->setParameters(new ArrayCollection(array(new Parameter("username", $username), new Parameter("email", $email))))
                    ->getQuery()->getResult();
    }
    public function getActivesForNotification() {
        //         case "playersSelectForEmailNotifications":
        //             $query =
        //             "SELECT player_id, CONCAT(player_first_name, ' ', player_last_name) AS name, player_email " .
        //             "FROM poker_players p " .
        //             "WHERE " . $this->buildPlayerActive(alias: "p") .
        //             " AND player_approval_date IS NOT NULL " .
        //             "AND player_rejection_date IS NULL " .
        //             "AND player_email <>  '' " .
        //             "ORDER BY player_id";
        return $this->createQueryBuilder("p")
                    ->addSelect("pa, pr")
                    ->leftJoin("p.playerApproval", "pa")
                    ->leftJoin("p.playerRejection", "pr")
                    ->where("p.playerActiveFlag = " . Constant::FLAG_YES_DATABASE)
                    ->andWhere("p.playerApprovalDate IS NOT NULL")
                    ->andWhere("p.playerRejectionDate IS NULL")
                    ->addOrderBy("p.playerId")
                    ->getQuery()->getResult();
    }

    public function getById(?int $playerId) {
        //      case "playersSelectAll":
        //      case "playersSelectById":
        $qb = $this->createQueryBuilder("p")
                    ->addSelect("pa, pr")
                    ->leftJoin("p.playerApproval", "pa")
                    ->leftJoin("p.playerRejection", "pr");
        if (isset($playerId)) {
            $qb = $qb->where("p.playerId = :playerId");
            $qb->setParameters(new ArrayCollection(array(new Parameter("playerId", $playerId))));
        } else {
            $qb->addOrderBy("p.playerLastName, p.playerFirstName", "ASC");
        }
        return $qb->getQuery()->getResult();
    }

    public function getChampionshipEarnings(string $groupBy, bool $group, bool $indexed) {
        //         case "championship":
        $sql = "SELECT ";
        if (!isset($group) || !$group) {
            $sql .= " yr, player_id, ";
        }
        $sql .= "name, ";
        if (isset($group) && $group) {
            $sql .= "SUM(earnings) AS ";
        }
        $queryAndBindParams = $this->buildChampionship(array(NULL, NULL));
        $sql .=
            "earnings, SUM(earnings) / trnys AS avg, trnys " .
            "FROM (" . $queryAndBindParams[0] . ") a " .
            "WHERE earnings > 0 " .
            "GROUP BY " . $groupBy;
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        if (isset($queryAndBindParams[1])) {
            foreach ($queryAndBindParams[1] as $key => $value) {
                $statement->bindValue($key, $value, PDO::PARAM_STR);
            }
        }
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }
    public function getChampionshipQualified(DateTime $startDate, DateTime $endDate, int $numTourneys, bool $indexed) {
        //         case "championshipQualifiedPlayers":
        $sql =
            "SELECT p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, " .
            "       SUM((np.numPlayers - r.result_place_finished + 1) * IFNULL(st.special_type_multiplier, 1) + IF(r.result_place_finished BETWEEN 1 AND se.season_final_table_players, se.season_final_table_bonus_points, 0)) AS points, " .
            "       SUM(IF(r.result_place_finished BETWEEN 1 AND se.season_final_table_players, se.season_final_table_bonus_points, 0)) AS 'bonus points', " .
            "       nt.numTourneys AS tourneys, " .
            "       SUM((np.numPlayers - r.result_place_finished + 1) * IFNULL(st.special_type_multiplier, 1) + IF(r.result_place_finished BETWEEN 1 AND se.season_final_table_players, se.season_final_table_bonus_points, 0)) / nt.numTourneys AS 'average points', " .
            "       ta.player_id AS playerIdAbsence, IF(ta.player_id IS NULL, 'Attending', 'Not attending') AS 'absence status' " .
            "FROM poker_players p INNER JOIN poker_results r ON p.player_id = r.player_id AND p.player_active_flag = " . Constant::FLAG_YES_DATABASE .
            " INNER JOIN poker_tournaments t on r.tournament_id = t.tournament_id AND t.tournament_date BETWEEN :startDate1 AND :endDate1 " .
            "LEFT JOIN poker_special_types st ON t.special_type_Id = st.special_type_Id " .
            "INNER JOIN (SELECT r1.player_id, COUNT(*) AS numTourneys " .
            "            FROM poker_results r1 " .
            "            INNER JOIN poker_tournaments t1 ON r1.tournament_id = t1.tournament_id " .
            "            WHERE r1.result_place_finished > 0 " .
            "            AND t1.tournament_date BETWEEN :startDate2 AND :endDate2 " .
            "            GROUP BY r1.player_id) nt ON r.player_id = nt.player_id " .
            "INNER JOIN (SELECT tournament_id, COUNT(*) AS numPlayers " .
            "            FROM poker_results " .
            "            WHERE result_place_finished > 0 " .
            "            GROUP BY tournament_id) np ON r.tournament_id = np.tournament_id " .
            "INNER JOIN poker_seasons se ON t.tournament_date BETWEEN se.season_start_date AND se.season_end_date " .
            "LEFT JOIN (SELECT t.tournament_id, t.tournament_date FROM poker_tournaments t INNER JOIN poker_special_types st ON t.special_type_id = st.special_type_Id WHERE st.special_type_description = '" . Constant::DESCRIPTION_CHAMPIONSHIP . "') tc ON tc.tournament_date BETWEEN se.season_start_date AND se.season_end_date " .
            "LEFT JOIN poker_tournament_absences ta ON tc.tournament_id = ta.tournament_id AND p.player_id = ta.player_id " .
            "LEFT JOIN poker_results ra ON ta.tournament_id = ra.tournament_id AND ta.player_id = ra.player_id " .
            "WHERE nt.numTourneys >= :numTourneys " .
            "GROUP BY r.player_id " .
            "ORDER BY points DESC";
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        if (isset($startDate) && isset($endDate)) {
            $startDateFormatted = DateTimeUtility::formatDatabaseDate(value: $startDate);
            $endDateFormatted = DateTimeUtility::formatDatabaseDate(value: $endDate);
            $statement->bindValue("startDate1", $startDateFormatted, PDO::PARAM_STR);
            $statement->bindValue("endDate1", $endDateFormatted, PDO::PARAM_STR);
            $statement->bindValue("startDate2", $startDateFormatted, PDO::PARAM_STR);
            $statement->bindValue("endDate2", $endDateFormatted, PDO::PARAM_STR);
            $statement->bindValue("numTourneys", $numTourneys, PDO::PARAM_INT);
        }
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }

    public function getFeeDetail(bool $indexed) {
        //         case "feeDetail":
        $sql =
            "SELECT s.season_id, s.season_description AS description, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, f.tournament_id, s.season_fee AS fee, IFNULL(f.fee_amount, 0) AS paid, IF(f.fee_amount IS NULL, s.season_fee, s.season_fee - f.fee_amount) - IF(fh.player_id IS NULL, 0, s.season_fee) AS balance " .
            "FROM poker_players p CROSS JOIN poker_seasons s ON p.player_active_flag = " . Constant::FLAG_YES_DATABASE .
            " LEFT JOIN poker_fees f ON s.season_id = f.season_id AND p.player_id = f.player_id AND f.fee_amount > 0 " .
            "LEFT JOIN (SELECT DISTINCT se.season_id, l.location_id, l.location_name, l.player_id, p.player_first_name, p.player_last_name " .
            "           FROM poker_tournaments t INNER JOIN poker_seasons se ON t.tournament_date BETWEEN se.season_start_date AND se.season_end_date " .
            "           INNER JOIN poker_locations l ON t.location_id = l.location_id " .
            "           INNER JOIN poker_players p ON l.player_id = p.player_id " .
            "           GROUP BY se.season_id, l.location_id) fh ON s.season_id = fh.season_id AND p.player_id = fh.player_id " .
            "ORDER BY s.season_description, f.player_id";
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
        //         $qb = $this->createQueryBuilder("p");
        //         return $qb->select("p, s, f")
        //                   ->innerJoin(Seasons::class, "s", Expr\Join::WITH, "p.playerActiveFlag = " . Constant::FLAG_YES_DATABASE)
        //                   ->leftJoin("s.fees", "f", Expr\Join::WITH, "p.playerId = f.players AND f.feeAmount > 0")
        //                   ->getQuery()->getResult();
    }

    public function getFeeForPlayerAndTournament(int $playerId, int $tournamentId) {
        //         case "feeSelectByTournamentIdAndPlayerId":
        $sql =
            "SELECT se.season_id, p.player_id, " .
            "IFNULL(f.fee_amount, 0) AS fee_amount, IF(se.season_fee IS NULL, '', IF(se.season_fee - IFNULL(f.fee_amount, 0) = 0, 'Paid', CONCAT('Owes $', (se.season_fee - IFNULL(f.fee_amount, 0))))) AS status " .
            "FROM poker_players p INNER JOIN poker_tournaments t ON p.player_id = :playerId AND t.tournament_id = :tournamentId " .
            "INNER JOIN poker_seasons se ON t.tournament_date BETWEEN se.season_start_date AND se.season_end_date " .
            "LEFT JOIN (SELECT season_id, player_id, SUM(fee_amount) AS fee_amount FROM poker_fees GROUP BY season_id, player_id) f ON se.season_id = f.season_id AND p.player_id = f.player_id";
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->bindValue("playerId", $playerId, PDO::PARAM_INT);
        $statement->bindValue("tournamentId", $tournamentId, PDO::PARAM_INT);
        return $statement->executeQuery()->fetchAllAssociative();
    }

    public function getFinishesForDates(int $playerId, ?DateTime $startDate, ?DateTime $endDate, bool $indexed) {
        //         case "finishesSelectAllByPlayerId":
        $sql =
            "SELECT a.result_place_finished AS place, IFNULL(b.finishes, 0) AS finishes, IFNULL(b.pct, 0) AS pct " .
            "FROM (SELECT DISTINCT result_place_finished " .
            "      FROM poker_results " .
            "      WHERE result_place_finished > 0 " .
            "      ORDER BY result_place_finished) a " .
            "LEFT JOIN (SELECT r1.result_place_finished, COUNT(*) AS finishes, COUNT(*) / (SELECT COUNT(*) " .
            "                                                                              FROM poker_results r2 " .
            "                                                                              INNER JOIN poker_tournaments t2 ON r2.tournament_id = t2.tournament_id";
        if (isset($startDate) && isset($endDate)) {
            $sql .= "                                                                      AND t2.tournament_date BETWEEN :startDate1 AND :endDate1 ";
        }
        $sql .=
            "                                                                              WHERE r2.player_id = r1.player_id) AS pct " .
            "           FROM poker_results r1 " .
            "           INNER JOIN poker_tournaments t1 ON r1.tournament_id = t1.tournament_id";
        if (isset($startDate) && isset($endDate)) {
            $sql .= " AND t1.tournament_date BETWEEN :startDate2 AND :endDate2";
        }
        $sql .=
            "           AND r1.player_id = :playerId" .
            "           GROUP BY r1.result_place_finished) b ON a.result_place_finished = b.result_place_finished";
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->bindValue("playerId", $playerId, PDO::PARAM_INT);
        if (isset($startDate) && isset($endDate)) {
            $startDateFormatted = DateTimeUtility::formatDatabaseDate(value: $startDate);
            $endDateFormatted = DateTimeUtility::formatDatabaseDate(value: $endDate);
            $statement->bindValue("startDate1", $startDateFormatted, PDO::PARAM_STR);
            $statement->bindValue("endDate1", $endDateFormatted, PDO::PARAM_STR);
            $statement->bindValue("startDate2", $startDateFormatted, PDO::PARAM_STR);
            $statement->bindValue("endDate2", $endDateFormatted, PDO::PARAM_STR);
        }
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }

    public function getResults(int $tournamentId, int $playerId) {
        //         case "foodByTournamentIdAndPlayerId":
        //             $query =
        //             "SELECT CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, p.player_email, r.result_registration_food, p.player_active_flag " .
        //             "FROM poker_players p LEFT JOIN poker_results r ON p.player_id = r.player_id AND r.tournament_id = :tournamentId " .
        //             "WHERE p.player_id = :playerId";
        $qb = $this->createQueryBuilder("p");
        return $qb->select("p, r")
                  ->leftJoin("p.results", "r", Expr\Join::WITH, "r.tournaments = :tournamentId")
                  ->where("p.playerId = :playerId")
                  ->setParameters(new ArrayCollection(array(new Parameter("tournamentId", $tournamentId), new Parameter("playerId", $playerId))))
                  ->getQuery()->getResult();
    }

    public function getKo(?int $playerId, ?DateTime $startDate, ?DateTime $endDate, ?array $orderBy, bool $rank, ?int $limitCount, bool $indexed) {
        //         case "knockoutsAverageForSeason":
        //         case "knockoutsTotalForSeason":
        //         case "knockoutsTotalAndAverageForSeasonForPlayer":
        //         case "knockoutsTotalAndAverageForPlayer":
        $sql =
            "SELECT p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, IFNULL(kO, 0) AS ko, IFNULL(avg, 0) AS avg, IFNULL(trnys, 0) AS trnys, p.player_active_flag " .
            "FROM poker_players p LEFT JOIN (SELECT k.player_id, k.knockouts AS kO, ROUND(k.knockouts / nt.numTourneys, 2) AS avg, nt.numTourneys AS trnys " .
            "                             FROM (SELECT p.player_id, p.player_first_name, p.player_last_name, COUNT(*) AS knockouts " .
            "                                   FROM poker_tournaments t " .
            "                                   INNER JOIN poker_results r ON t.tournament_id = r.tournament_id ";
        if (isset($startDate) && isset($endDate)) {
            $sql .= "                                   AND t.tournament_date BETWEEN :startDate1 AND :endDate1 ";
        }
        $sql .=
            "                                   AND r.result_place_finished > 0 " .
            "                                   INNER JOIN poker_players p ON r.player_id_ko = p.player_id " .
            "                                   GROUP BY r.player_id_ko) k " .
            "INNER JOIN (SELECT r.player_id, COUNT(*) AS numTourneys " .
        "            FROM poker_tournaments t INNER JOIN poker_results r ON t.tournament_id = r.tournament_id AND r.result_place_finished > 0 ";
        if (isset($startDate) && isset($endDate)) {
            $sql .= "   AND t.tournament_date BETWEEN :startDate2 AND :endDate2 ";
        }
        $sql .=
            "    GROUP BY r.player_id) nt ON k.player_id = nt.player_id) a ON p.player_id = a.player_id " .
            "WHERE p.player_active_flag = " . Constant::FLAG_YES_DATABASE;
        if (isset($playerId)) {
            $whereClause = "AND p.player_id = " . $playerId;
            $sql .= " AND p.player_id = " . $playerId;
        } else {
        }
        if (!$rank && isset($orderBy)) {
            $sql .= " ORDER BY ";
            if (1 == $orderBy[0]) {
                $sql .= "ko";
            } else {
                $sql .= "avg";
            }
            $sql .= " DESC, player_last_name, player_first_name";
        }
        if ($rank) {
            if (1 == $orderBy[0]) {
                $orderByFieldName = "ko DESC, player_last_name, player_first_name";
                $selectFieldName = "ko";
            } else {
                $orderByFieldName = "avg DESC, player_last_name, player_first_name";
                $selectFieldName = "avg";
            }
            $selectFieldNames = "player_id, name, ko, avg, trnys";
            $sql = $this->modifyQueryAddRank(query: $sql, whereClause: $whereClause, selectFieldName: $selectFieldName, selectFieldNames: $selectFieldNames, orderByFieldName: $orderByFieldName);
        }
        if (isset($limitCount)) {
            $sql .= " LIMIT :limitCount";
        }
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        if (isset($startDate) && isset($endDate)) {
            $startDateFormatted = DateTimeUtility::formatDatabaseDate(value: $startDate);
            $endDateFormatted = DateTimeUtility::formatDatabaseDate(value: $endDate);
            $statement->bindValue("startDate1", $startDateFormatted, PDO::PARAM_STR);
            $statement->bindValue("endDate1", $endDateFormatted, PDO::PARAM_STR);
            $statement->bindValue("startDate2", $startDateFormatted, PDO::PARAM_STR);
            $statement->bindValue("endDate2", $endDateFormatted, PDO::PARAM_STR);
        }
        //         if (isset($playerId)) {
        //             $statement->bindValue("playerId", $playerId, PDO::PARAM_INT);
        //         }
        if (isset($limitCount)) {
            $statement->bindValue(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }

    public function getPoints(?int $playerId, ?DateTime $startDate, ?DateTime $endDate, ?array $orderBy, bool $rank, ?int $limitCount, bool $indexed) {
        //         case "pointsAverageForSeason":
        //         case "pointsTotalForSeason":
        //         case "pointsTotalAndAverageForSeasonForPlayer":
        //         case "pointsTotalAndAverageForPlayer":
        $sql =
            "SELECT p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, IFNULL(a.points, 0) AS pts, IFNULL(ROUND(a.points / a.trnys, 2), 0) AS avg, IFNULL(a.trnys, 0) AS trnys, p.player_active_flag " .
            "FROM poker_players p LEFT JOIN (SELECT p.player_id, SUM((np.numPlayers - r.result_place_finished + 1) * IFNULL(st.special_type_multiplier, 1) + IF(r.result_place_finished BETWEEN 1 AND se.season_final_table_players, se.season_final_table_bonus_points, 0)) AS points, nt.trnys " .
            "                            FROM poker_players p INNER JOIN poker_results r ON p.player_id = r.player_id " .
            "                            INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id ";
        if (isset($startDate) && isset($endDate)) {
            $sql .= "                             AND t.tournament_date BETWEEN :startDate1 AND :endDate1 ";
        }
        $sql .=
            "                            INNER JOIN poker_seasons se ON t.tournament_date BETWEEN se.season_start_date AND se.season_end_date " .
            "                            LEFT JOIN poker_special_types st ON t.special_type_id = st.special_type_id" .
            "                            INNER JOIN (SELECT r1.player_id, COUNT(*) AS trnys " .
            "                                        FROM poker_results r1 " .
            "                                        INNER JOIN poker_tournaments t1 ON r1.tournament_id = t1.tournament_id AND r1.result_place_finished > 0 ";
        if (isset($startDate) && isset($endDate)) {
            $sql .= "                                         AND t1.tournament_date BETWEEN :startDate2 AND :endDate2 ";
        }
        $sql .=
            "                                        GROUP BY r1.player_id) nt ON r.player_id = nt.player_id " .
            "                            INNER JOIN (SELECT tournament_id, COUNT(*) AS numPlayers " .
            "                                        FROM poker_results " .
            "                                        WHERE result_place_finished > 0 " .
            "                                        GROUP BY tournament_id) np ON r.tournament_id = np.tournament_id " .
            "WHERE st.special_type_description IS NULL OR st.special_type_description <> '" . Constant::DESCRIPTION_CHAMPIONSHIP ."' " .
            "GROUP BY r.player_id) a ON p.player_id = a.player_id " .
            "WHERE p.player_active_flag = " . Constant::FLAG_YES_DATABASE;
        if (isset($playerId)) {
            $whereClause = " AND p.player_id = " . $playerId;
            $sql .= " AND p.player_id = " . $playerId;
        }
        if (!$rank && isset($orderBy)) {
            $sql .= " ORDER BY ";
            if (1 == $orderBy[0]) {
                $sql .= "pts";
            } else {
                $sql .= "avg";
            }
            $sql .= " DESC, player_last_name, player_first_name";
        }
        if ($rank) {
            if (1 == $orderBy[0]) {
                $orderByFieldName = "pts DESC, player_last_name, player_first_name";
                $selectFieldName = "pts";
            } else {
                $orderByFieldName = "avg DESC, player_last_name, player_first_name";
                $selectFieldName = "avg";
            }
            $selectFieldNames = "player_id, name, pts, avg, trnys";
            $sql = $this->modifyQueryAddRank(query: $sql, whereClause: $whereClause, selectFieldName: $selectFieldName, selectFieldNames: $selectFieldNames, orderByFieldName: $orderByFieldName);
        }
        if (isset($limitCount)) {
            $sql .= " LIMIT :limitCount";
        }
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        if (isset($startDate) && isset($endDate)) {
            $startDateFormatted = DateTimeUtility::formatDatabaseDate(value: $startDate);
            $endDateFormatted = DateTimeUtility::formatDatabaseDate(value: $endDate);
            $statement->bindValue("startDate1", $startDateFormatted, PDO::PARAM_STR);
            $statement->bindValue("endDate1", $endDateFormatted, PDO::PARAM_STR);
            $statement->bindValue("startDate2", $startDateFormatted, PDO::PARAM_STR);
            $statement->bindValue("endDate2", $endDateFormatted, PDO::PARAM_STR);
        }
        if (isset($limitCount)) {
            $statement->bindValue(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }

    public function getStatuses(int $tournamentId, bool $indexed) {
        //         case "statusSelectPaid":
        $sql =
            "SELECT p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, " .
            "       IF(s.season_fee - IFNULL(f.fee_amount, 0) - IF(fh.player_id IS NULL, 0, s.season_fee) = 0, 'Paid', 'Not paid') AS fee_status_logic, " .
            "       IF(s.season_fee IS NULL, '', IF(s.season_fee - IFNULL(f.fee_amount, 0) - IF(fh.player_id IS NULL, 0, s.season_fee) = 0, 'Paid', CONCAT('Owes ', (s.season_fee - IFNULL(f.fee_amount, 0))))) AS 'fee status', " .
            "       IFNULL(f.fee_amount, 0) AS fee_amount, " .
            "       IF(f.fee_amount = s.season_fee, f.fee_amount, IFNULL(f2.fee_amount, 0) + IF(fh.player_id IS NULL, 0, s.season_fee)) AS amount2, " .
            "       IF(r.result_paid_buyin_flag = '" . Constant::FLAG_YES . "', 'Paid', 'Not paid') AS buyin_status, " .
            "       IF(r.result_paid_rebuy_flag = '" . Constant::FLAG_YES . "', 'Paid', 'Not paid') AS rebuy_status, " .
            "       r.result_rebuy_count, IF(r.result_paid_addon_flag = '" . Constant::FLAG_YES . "', 'Paid', 'Not paid') AS addon_status " .
            "FROM poker_players p INNER JOIN poker_results r ON p.player_id = r.player_id AND p.player_active_flag = " . Constant::FLAG_YES_DATABASE .
            " INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id AND t.tournament_id = :tournamentId AND r.status_code IN ('" . Constant::CODE_STATUS_REGISTERED . "', '" . Constant::CODE_STATUS_PAID . "') AND r.result_registration_order <= t.tournament_max_players" .
            " INNER JOIN poker_seasons s ON t.tournament_date BETWEEN s.season_start_date AND s.season_end_date" .
            " LEFT JOIN (SELECT season_id, player_id, SUM(fee_amount) AS fee_amount FROM poker_fees GROUP BY season_id, player_id) f ON s.season_id = f.season_id AND p.player_id = f.player_id" .
            " LEFT JOIN (SELECT season_id, player_id, tournament_id, fee_amount FROM poker_fees) f2 ON s.season_id = f2.season_id AND p.player_id = f2.player_id AND t.tournament_id = f2.tournament_id" .
            " LEFT JOIN (SELECT DISTINCT se.season_id, l.location_id, l.location_name, l.player_id, p.player_first_name, p.player_last_name " .
            "            FROM poker_tournaments t INNER JOIN poker_seasons se ON t.tournament_date BETWEEN se.season_start_date AND se.season_end_date " .
            "            INNER JOIN poker_locations l ON t.location_id = l.location_id " .
            "            INNER JOIN poker_players p ON l.player_id = p.player_id " .
            "            GROUP BY se.season_id, l.location_id) fh ON s.season_id = fh.season_id AND p.player_id = fh.player_id";
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->bindValue("tournamentId", $tournamentId, PDO::PARAM_INT);
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }

    public function getTournamentAbsences(DateTime $tournamentDate) {
        //         case "playerAbsencesByTournamentId":
        //         SELECT ta.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS NAME
        //         FROM poker_players p INNER JOIN poker_tournament_absences ta ON p.player_id = ta.player_id
        //         INNER JOIN poker_tournaments t ON ta.tournament_id = t.tournament_id
        //         INNER JOIN poker_seasons s ON t.tournament_date BETWEEN s.season_start_date AND s.season_end_date AND '2020-01-01' BETWEEN s.season_start_date AND s.season_end_date
        //         INNER JOIN poker_special_types st ON t.special_type_id = st.special_type_id AND st.special_type_description = 'Championship';
        $qb = $this->createQueryBuilder("p");
        $tournamentDateFormatted = DateTimeUtility::formatDatabaseDate(value: $tournamentDate);
        return $qb->select("p")
                  ->innerJoin("p.tournamentAbsences", "ta")
                  ->innerJoin("ta.tournaments", "t")
                  ->innerJoin("t.specialTypes", "st")
                  ->innerJoin(Seasons::class, "s", Expr\Join::WITH, "t.tournamentDate BETWEEN s.seasonStartDate AND s.seasonEndDate")
                  ->where(":tournamentDate BETWEEN s.seasonStartDate AND s.seasonEndDate")
                  ->andWhere("st.specialTypeDescription = '" . Constant::DESCRIPTION_CHAMPIONSHIP . "'")
                  ->setParameters(new ArrayCollection(array(new Parameter("tournamentDate", $tournamentDateFormatted))))
                  ->getQuery()->getResult();
    }

    public function getForApproval(bool $indexed) {
        //         case "playersSelectForApproval":
        $sql =
            "SELECT p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, p.player_email, p.player_username, p.player_rejection_date AS 'Rejection Date', CONCAT(p2.player_first_name, ' ', p2.player_last_name) AS 'Rejection Name' " .
            "FROM poker_players p " .
            "LEFT JOIN poker_players p2 ON p.player_rejection_player_id = p2.player_id " .
            "WHERE p.player_approval_date IS NULL AND p.player_rejection_date IS NULL";
        //         return $this->createQueryBuilder("p")
        //                     ->where("p.playerApprovalDate IS NULL")
        //                     ->andWhere("p.playerRejectionDate IS NULL")
        //                     ->getQuery()->getResult();
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }

    public function getWaitListed(int $tournamentId) {
        //         case "waitListedPlayerByTournamentId":
        //             $query =
        //             "SELECT p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, p.player_email, t.tournament_max_players " .
        //             "FROM poker_players p " .
        //             "INNER JOIN poker_results r ON p.player_id = r.player_id AND r.tournament_id = :tournamentId AND " . $this->buildPlayerActive(alias: "p") .
        //             " INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id AND r.result_registration_order = t.tournament_max_players";
        $qb = $this->createQueryBuilder("p");
        return $qb->select("p")
                  ->innerJoin("p.results", "r", Expr\Join::WITH, "r.tournaments = :tournamentId AND p.playerActiveFlag = " . Constant::FLAG_YES_DATABASE)
                  ->innerJoin("r.tournaments", "t", Expr\Join::WITH, "r.resultRegistrationOrder = t.tournamentMaxPlayers")
                  ->setParameters(new ArrayCollection(array(new Parameter("tournamentId", $tournamentId))))
                  ->getQuery()->getResult();
    }

    public function getWins(?DateTime $startDate, ?DateTime $endDate, ?int $playerId, bool $winsForPlayer, bool $winsForSeason, bool $rank, array $orderBy, bool $indexed) {
        //         case "winnersForSeason":
        //         case "winsForPlayer":
        //         case "winsTotalAndAverageForSeasonForPlayer":
        $sql =
            "SELECT p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, IFNULL(wins, 0) AS wins, IFNULL(wins / trnys, 0) AS avg, IFNULL(trnys, 0) AS trnys, p.player_active_flag " .
            "FROM poker_players p " .
            "LEFT JOIN (SELECT r.player_id, COUNT(*) AS wins, numTourneys AS trnys " .
            "           FROM poker_tournaments t " .
            "           INNER JOIN poker_results r ON t.tournament_id = r.tournament_id AND r.result_place_finished = " . Constant::FLAG_YES_DATABASE;
        if (!$winsForPlayer && isset($startDate) && isset($endDate)) {
            $sql .= "            AND t.tournament_date BETWEEN :startDate1 AND :endDate1 ";
        }
        $sql .=
            "            INNER JOIN (SELECT r.player_id, COUNT(*) AS numTourneys " .
            "                        FROM poker_results r " .
            "                        INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id AND r.result_place_finished > 0 ";
        if (!$winsForPlayer && isset($startDate) && isset($endDate)) {
            $sql .= "                        AND t.tournament_date BETWEEN :startDate2 AND :endDate2 ";
        }
        $sql .=
            "                        GROUP BY r.player_id) nt ON r.player_id = nt.player_id " .
            "            GROUP BY r.player_id) a ON p.player_id = a.player_id AND p.player_active_flag = " . Constant::FLAG_YES_DATABASE .
            " WHERE p.player_active_flag = " . Constant::FLAG_YES_DATABASE;
        if (isset($playerId)) {
            $whereClause = " AND p.player_id = :playerId";
            $sql .= " AND p.player_id = :playerId";
        } else if ($winsForSeason) {
            $sql .= " AND wins > 0";
        }
        if ($winsForSeason) {
            $sql .= " ORDER BY wins DESC, p.player_last_name, p.player_first_name";
        }
        if ($rank) {
            if (1 == $orderBy[0]) {
                $orderByFieldName = "wins DESC, p.player_last_name, p.player_first_name";
                $selectFieldName = "wins";
            } else {
                $orderByFieldName = "avg DESC, p.player_last_name, p.player_first_name";
                $selectFieldName = "avg";
            }
            $selectFieldNames = "player_id, name, wins, avg, trnys";
            $sql = $this->modifyQueryAddRank(query: $sql, whereClause: $whereClause, selectFieldName: $selectFieldName, selectFieldNames: $selectFieldNames, orderByFieldName: $orderByFieldName);
        }
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        if (!$winsForPlayer && isset($startDate) && isset($endDate)) {
            $startDateFormatted = DateTimeUtility::formatDatabaseDate(value: $startDate);
            $endDateFormatted = DateTimeUtility::formatDatabaseDate(value: $endDate);
            $statement->bindValue("startDate1", $startDateFormatted, PDO::PARAM_STR);
            $statement->bindValue("endDate1", $endDateFormatted, PDO::PARAM_STR);
            $statement->bindValue("startDate2", $startDateFormatted, PDO::PARAM_STR);
            $statement->bindValue("endDate2", $endDateFormatted, PDO::PARAM_STR);
        }
        //         if (isset($playerId)) {
        //             $statement->bindValue("playerId", $playerId, PDO::PARAM_INT);
        //         }
        //         echo "<br>aft->".$sql;
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }

    public function getTableOutput(?int $playerId, bool $indexed) {
        $sql =
            "SELECT p.player_id AS id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, p.player_username AS username, p.player_password, p.player_email AS email, p.player_phone AS phone, p.player_administrator_flag AS admin, p.player_registration_date AS 'Reg Date', p.player_approval_date AS 'App Date', p.player_approval_player_id AS 'App User', CONCAT(ua.player_first_name, ' ', ua.player_last_name) AS 'App Name', p.player_rejection_date AS 'Reject Date', p.player_rejection_player_id AS 'Reject User', CONCAT(ur.player_first_name, ' ', ur.player_last_name) AS 'Reject Name', p.player_active_flag AS active " .
            "FROM poker_players p " .
            "LEFT JOIN poker_players ua ON p.player_approval_player_id = ua.player_id " .
            "LEFT JOIN poker_players ur ON p.player_rejection_player_id = ur.player_id";
        if (isset($playerId)) {
            $sql .= "WHERE p.player_id = :playerId";
        }
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        if (isset($playerId)) {
            $statement->bindValue("playerId", $playerId, PDO::PARAM_INT);
        }
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }

    public function updateForReset(?string $password, ?string $selectorTemp, ?string $tokenTemp, ?DateTime $expiresTemp, string $username, string $email) {
        if (isset($password)) {
            $selector = NULL;
            $token = NULL;
            $hash = NULL;
            $expiresFormatted = NULL;
        } else {
            $selector = bin2hex(random_bytes(length: 8));
            $token = random_bytes(length: 32);
            $hash = hash(algo: 'sha256', data: $token);
            $expires = new DateTime();
            $expires->add(new DateInterval("P1D")); // 1 day
            $expiresFormatted = DateTimeUtility::formatSecondsSinceEpoch(value: $expires);
        }
        $sql =
            "UPDATE poker_players " .
            "SET " . (isset($password) ? "player_password = ?, " : "") .
            "player_selector = ?, player_token = ?, player_expires = ? " .
            "WHERE player_username = ? " .
            "AND player_email = ?";
        $params = array();
        $paramTypes = array();
        if (isset($password)) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            array_push($params, $hash);
            array_push($paramTypes, PDO::PARAM_STR);
        }
        array_push($params, $selector, $hash, $expiresFormatted, $username, $email);
        array_push($paramTypes, PDO::PARAM_STR, PDO::PARAM_STR, PDO::PARAM_STR, PDO::PARAM_STR, PDO::PARAM_STR);
        $recordCount = $this->getEntityManager()->getConnection()->executeStatement($sql, $params, $paramTypes);
        if (isset($password)) {
            $returnValue = $recordCount;
        } else {
            if (1 == $recordCount) {
                $returnValue = array($selector, bin2hex($token), $expires->format('U'));
            } else {
                $returnValue = "More than 1 record found!";
            }
        }
        return $returnValue;
    }
}