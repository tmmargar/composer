<?php
namespace Poker\Ccp\Entity;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\Parameter;
use PDO;
use Poker\Ccp\classes\model\Constant;
use Poker\Ccp\classes\utility\DateTimeUtility;
class TournamentsRepository extends BaseRepository {
    public function getById(?int $tournamentId) {
        $qb = $this->createQueryBuilder("t")
                   ->addSelect("t, lt, gt, st, l, gr")
                   ->innerJoin("t.limitTypes", "lt")
                   ->innerJoin("t.gameTypes", "gt")
                   ->leftJoin("t.specialTypes", "st")
                   ->innerJoin("t.locations", "l")
                   ->innerJoin("t.groups", "gr")
                   ;
        // groups, fees, absences, results
        if (isset($tournamentId)) {
            $qb = $qb->where("t.tournamentId = :tournamentId");
            $qb->setParameters(new ArrayCollection(array(new Parameter("tournamentId", $tournamentId))));
        }
        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getRegisterHost(DateTime $startDate, DateTime $endDate) {
//         case "autoRegisterHost":
//         "SELECT t.tournament_id, t.tournament_date, t.tournament_start_time, l.player_id, l.location_address, l.location_city, l.location_state, l.location_zip_code, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, p.player_email " .
//         "FROM poker_tournaments t " .
//         "INNER JOIN poker_locations l ON t.location_id = l.location_id AND t.tournament_date BETWEEN :startDate AND DATE_ADD(:endDate, INTERVAL 14 DAY) " .
//         "INNER JOIN poker_players p ON l.player_id = p.player_id AND " . $this->buildPlayerActive(alias: "p") .
//         " LEFT JOIN poker_results r ON t.tournament_id = r.tournament_id AND p.player_id = r.player_id " .
//         "WHERE r.player_id IS NULL";
        $qb = $this->createQueryBuilder("t");
        $dtStartFormatted = DateTimeUtility::formatDatabaseDate(value: $startDate);
        $dtEndFormatted = DateTimeUtility::formatDatabaseDate(value: $endDate);
        return $qb->innerJoin("t.locations", "l", Expr\Join::WITH, $qb->expr()->between("t.tournamentDate", ":startDate", "DATE_ADD(:endDate, 14, 'DAY')"))
                  ->innerJoin("l.players", "p", Expr\Join::WITH, "p.playerActiveFlag = 1")
                  ->leftJoin("t.results", "r", Expr\Join::WITH, "p.playerId = r.players")
                  ->where("r.players IS NULL")
                  ->setParameters(new ArrayCollection(array(new Parameter("startDate", $dtStartFormatted), new Parameter("endDate", $dtEndFormatted))))
                  ->getQuery()->getResult();
    }

    public function getCountForDates(DateTime $startDate, DateTime $endDate) {
//         case "countTournamentForDates": // 0 is start date, 1 is end date, 2 is NULL, 3 is false
//             "SELECT COUNT(*) AS cnt " .
//             "FROM poker_tournaments t";
//             if (isset($params[3]) && $params[3]) {
//                 $query .= " INNER JOIN poker_results r ON t.tournament_id = r.tournament_id";
//             }
//             if (isset($params[2])) {
//                 $query .=
//                 " WHERE r.player_id = :playerId" .
//                 " AND r.status_code = '" . Constant::CODE_STATUS_FINISHED . "' AND ";
//             }
//             $query .= "t.tournament_date BETWEEN :startDate AND :endDate";
        $qb = $this->createQueryBuilder("t");
        $startDateFormatted = DateTimeUtility::formatDatabaseDate(value: $startDate);
        $endDateFormatted = DateTimeUtility::formatDatabaseDate(value: $endDate);
        return $qb->where("t.tournamentDate BETWEEN :startDate AND :endDate")
                  ->setParameters(new ArrayCollection(array(new Parameter("startDate", $startDateFormatted), new Parameter("endDate", $endDateFormatted))))
                  ->getQuery()->getResult();
    }

    public function getCountForUserAndDates(int $playerId, DateTime $startDate, DateTime $endDate) {
        //         case "countTournamentForDates": // 0 is start date, 1 is end date, 2 is player id, 3 is true
        //             "SELECT COUNT(*) AS cnt " .
        //             "FROM poker_tournaments t";
        //             if (isset($params[3]) && $params[3]) {
        //                 $query .= " INNER JOIN poker_results r ON t.tournament_id = r.tournament_id";
        //             }
        //             if (isset($params[2])) {
        //                 $query .=
        //                 " WHERE r.player_id = :playerId" .
        //                 " AND r.status_code = '" . Constant::CODE_STATUS_FINISHED . "' AND ";
        //             }
        //             $query .= "t.tournament_date BETWEEN :startDate AND :endDate";
        $qb = $this->createQueryBuilder("t");
        $dtStartFormatted = DateTimeUtility::formatDatabaseDate(value: $startDate);
        $dtEndFormatted = DateTimeUtility::formatDatabaseDate(value: $endDate);
        return $qb->innerJoin("t.results", "r")
                  ->where("r.players = :playerId")
                  ->andWhere("r.statusCodes = :statusCode")
                  ->andWhere("t.tournamentDate BETWEEN :startDate AND :endDate")
                  ->setParameters(new ArrayCollection(array(new Parameter("playerId", $playerId), new Parameter("statusCode", Constant::CODE_STATUS_FINISHED), new Parameter("startDate", $dtStartFormatted), new Parameter("endDate", $dtEndFormatted))))
                  ->getQuery()->getResult();
    }

    public function getForYear(DateTime $tournamentDate) {
//         case "tournamentAll":
//             $query =
//             "SELECT tournament_id, tournament_description, tournament_date " .
//             "FROM poker_tournaments " .
//             "WHERE YEAR(tournament_date) = :tournamentDate " .
//             "ORDER BY tournament_date DESC, tournament_start_time DESC";
        $qb = $this->createQueryBuilder("t");
        return $qb->select("t")
                  ->where("YEAR(t.tournamentDate) = :tournamentDate")
                  ->addOrderBy("t.tournamentDate", "DESC")
                  ->addOrderBy("t.tournamentStartTime", "DESC")
                  ->setParameters(new ArrayCollection(array(new Parameter("tournamentDate", $tournamentDate))))
                  ->getQuery()->getResult();
    }
    public function getMaxId(int $seasonId) {
//         case "tournamentIdMax":
        $sql=
            "SELECT MAX(t.tournament_id) AS tournamentId " .
            "FROM poker_tournaments t INNER JOIN poker_seasons s ON t.tournament_date BETWEEN s.season_start_date AND s.season_end_date " .
            "WHERE s.season_id = :seasonId";
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->bindValue("seasonId", $seasonId, PDO::PARAM_INT);
        return $statement->executeQuery()->fetchAllNumeric();
//         $qb = $this->createQueryBuilder("t");
//         return $qb->select("MAX(t.tournamentId)")
// //                   ->innerJoin("t.results", "r")
//                   ->andWhere("t.tournamentDate BETWEEN ")
//                   ->setParameters(new ArrayCollection(array(new Parameter("seasonId", $seasonId))))
//                   ->getQuery()->getSingleResult();
    }
    public function getPrizePool(DateTime $startDate, DateTime $endDate) {
//         case "prizePoolForSeason":
        $sql =
            "SELECT SUM(total) AS total " .
            "FROM (SELECT t.tournament_id AS Id, np.play, " .
            "             ((t.tournament_buyin_amount * t.tournament_rake) * np.play) + " .
            "             ((t.tournament_rebuy_amount * t.tournament_rake) * IFNULL(nr.numRebuys, 0)) + " .
            "             ((t.tournament_addon_amount * t.tournament_rake) * IFNULL(na.numAddons, 0)) AS total " .
            "      FROM poker_tournaments t " .
            "      LEFT JOIN (SELECT tournament_id, COUNT(*) AS play " .
            "                 FROM poker_results " .
            "                 WHERE result_paid_buyin_flag = '" . Constant::FLAG_YES . "' " .
            "                 AND result_place_finished > 0 " .
            "                 GROUP BY tournament_id) np ON t.tournament_id = np.tournament_id " .
            "      LEFT JOIN (SELECT tournament_id, SUM(result_rebuy_count) AS numRebuys " .
            "                 FROM poker_results " .
            "                 WHERE result_paid_rebuy_flag = '" . Constant::FLAG_YES . "' " .
            "                 AND result_rebuy_count > 0 " .
            "                 GROUP BY tournament_id) nr ON t.tournament_id = nr.tournament_id " .
            "      LEFT JOIN (SELECT tournament_id, COUNT(*) AS numAddons " .
            "                 FROM poker_results " .
            "                 WHERE result_paid_addon_flag = '" . Constant::FLAG_YES . "' " .
            "                 GROUP BY tournament_id) na ON t.tournament_id = na.tournament_id " .
            "      WHERE t.tournament_date BETWEEN :startDate AND :endDate) zz";
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        if (isset($startDate) && isset($endDate)) {
            $startDateFormatted = DateTimeUtility::formatDatabaseDate(value: $startDate);
            $endDateFormatted = DateTimeUtility::formatDatabaseDate(value: $endDate);
            $statement->bindValue("startDate", $startDateFormatted, PDO::PARAM_STR);
            $statement->bindValue("endDate", $endDateFormatted, PDO::PARAM_STR);
        }
        return $statement->executeQuery()->fetchAllAssociative();
    }

    public function getRegistrationList(DateTime $tournamentDate, bool $max) {
//         case "registrationList":
        $sql =
            "SELECT p.player_first_name, p.player_last_name, r.result_registration_food, IF(s.season_fee - f.fee_amount = 0, 'Paid', CONCAT('Owes ', s.season_fee - f.fee_amount)) AS 'fee status' " .
            "FROM (SELECT t1.tournament_id, t1.tournament_date " .
            "      FROM poker_tournaments t1 INNER JOIN (SELECT tournament_date, MIN(tournament_start_time) startTimeMin, MAX(tournament_start_time) startTimeMax " .
            "                                           FROM poker_tournaments " .
            "                                           WHERE tournament_date = :tournamentDate) t2 " .
            "      ON t1.tournament_date = t2.tournament_date " .
            "      AND t1.tournament_start_time = t2.startTime" . ($max ? "Max" : "Min") . ") t INNER JOIN poker_results r ON t.tournament_id = r.tournament_id " .
            "INNER JOIN poker_players p ON r.player_id = p.player_id " .
            "INNER JOIN poker_seasons s ON t.tournament_date BETWEEN s.season_start_date AND s.season_end_date " .
            "INNER JOIN (SELECT season_id, player_id, SUM(fee_amount) AS fee_amount FROM poker_fees GROUP BY season_id, player_id) f ON s.season_id = f.season_id AND p.player_id = f.player_id " .
            "ORDER BY r.result_registration_order";
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        if (isset($tournamentDate)) {
            $tournamentDateFormatted = DateTimeUtility::formatDatabaseDate(value: $tournamentDate);
            $statement->bindValue("tournamentDate", $tournamentDateFormatted, PDO::PARAM_STR);
        }
        return $statement->executeQuery()->fetchAllAssociative();
    }

    public function getRegistrationWaitList(int $tournamentId, int $registerOrder) {
//         case "registrationWaitList":
//             $query =
//             "SELECT p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, p.player_email, t.tournament_max_players, p.player_active_flag " .
//             "FROM poker_players p " .
//             "INNER JOIN poker_results r ON p.player_id = r.player_id " .
//             "INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id AND r.tournament_id = :tournamentId AND r.result_registration_order > :registerOrder AND r.result_registration_order > t.tournament_max_players " .
//             "ORDER BY r.result_registration_order";
        $qb = $this->createQueryBuilder("t");
        return $qb->addSelect("r")
                  ->innerJoin("t.results", "r")
                  ->where("t.tournamentId = :tournamentId")
                  ->andWhere("r.resultRegistrationOrder > :registerOrder")
                  ->andWhere("r.resultRegistrationOrder > t.tournamentMaxPlayers")
                  ->addOrderBy("r.resultRegistrationOrder", "ASC")
                  ->setParameters(new ArrayCollection(array(new Parameter("tournamentId", $tournamentId), new Parameter("registerOrder", $registerOrder))))
                  ->getQuery()->getResult();
    }

    public function getResultsMaxId(DateTime $tournamentDate) { // max with results
//         case "resultIdMax":
        $tournamentDateFormatted = DateTimeUtility::formatDatabaseDate(value: $tournamentDate);
        $qb = $this->createQueryBuilder("t");
        return $qb->select("MAX(t.tournamentId) AS tournamentId")
                  ->innerJoin("t.results", "r")
                  ->where("t.tournamentDate <= :tournamentDate")
                  ->setParameters(new ArrayCollection(array(new Parameter("tournamentDate", $tournamentDateFormatted))))
                  ->getQuery()->getResult();
    }

    public function getAllMultiple(bool $championship, ?DateTime $tournamentDate, ?DateTime $startTime, ?int $tournamentId, bool $notEntered, bool $ordered, ?string $mode, ?int $interval, ?int $limitCount) {
//         case "tournamentSelectAll":
//         case "tournamentSelectAllByDateAndStartTime":
//         case "tournamentSelectOneById":
//         case "tournamentSelectAllForRegistration":
//         case "tournamentSelectAllForBuyins":
//         case "tournamentSelectAllForChampionship":
//         case "tournamentSelectAllOrdered":
//         case "tournamentsSelectForEmailNotifications":
        $sql =
            "SELECT t.tournament_id AS id, t.tournament_description AS description, t.tournament_comment AS comment, t.location_id, l.location_name AS location, t.limit_type_id, lt.limit_type_name AS 'limit', t.game_type_id, gt.game_type_name AS 'type', " .
            "       l.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS playerName, l.location_address, l.location_city, l.location_state, l.location_zip_code, p.player_phone, l.location_map AS mapHide, l.location_map_link AS map, t.tournament_date AS date, t.tournament_start_time AS 'start', t.tournament_max_players AS 'max players', t.tournament_chip_count AS 'chips', -t.tournament_buyin_amount AS 'buyin', t.tournament_max_rebuys AS 'max', -t.tournament_rebuy_amount AS 'amt', -t.tournament_addon_amount AS 'amt ', " .
            "       t.tournament_addon_chip_count AS 'chips ', t.group_id, g.group_name AS name, t.tournament_rake AS rake, IFNULL(nr.registeredCount, 0) AS registeredCount, " .
            "       IFNULL(bp.buyinsPaid, 0) AS buyinsPaid, " .
            "       IFNULL(rp.rebuysPaid, 0) AS rebuysPaid, " .
            "       IFNULL(rc.rebuysCount, 0) AS rebuysCount, " .
            "       IFNULL(ap.addonsPaid, 0) AS addonsPaid, " .
            "       IFNULL(ec.enteredCount, 0) AS enteredCount, " .
            "       t.special_type_id, st.special_type_description AS std, st.special_type_multiplier " .
            "FROM poker_tournaments t INNER JOIN poker_game_types gt ON t.game_type_id = gt.game_type_id " .
            "INNER JOIN poker_limit_types lt ON t.limit_type_id = lt.limit_type_id ";
        if ($championship) {
            $sql .= "INNER JOIN poker_special_types st ON t.special_type_id = st.special_type_id AND st.special_type_description = '" . Constant::DESCRIPTION_CHAMPIONSHIP . "' ";
        } else {
            $sql .= "LEFT JOIN poker_special_types st ON t.special_type_id = st.special_type_id ";
        }
        $sql .=
            "INNER JOIN poker_locations l ON t.location_id = l.location_id " .
            "INNER JOIN poker_players p ON l.player_id = p.player_id " .
            "INNER JOIN poker_groups g on t.group_id = g.group_id " .
            "LEFT JOIN (SELECT tournament_id, COUNT(*) AS buyinsPaid FROM poker_results WHERE result_paid_buyin_flag = '" . Constant::FLAG_YES . "' AND result_place_finished = 0 GROUP BY tournament_id) bp ON t.tournament_id = bp.tournament_id " .
            "LEFT JOIN (SELECT tournament_id, COUNT(*) AS rebuysPaid FROM poker_results WHERE result_paid_rebuy_flag = '" . Constant::FLAG_YES . "' AND result_place_finished = 0 GROUP BY tournament_id) rp ON t.tournament_id = rp.tournament_id " .
            "LEFT JOIN (SELECT tournament_id, SUM(result_rebuy_count) AS rebuysCount FROM poker_results WHERE result_paid_rebuy_flag = '" . Constant::FLAG_YES . "' AND result_place_finished = 0 GROUP BY tournament_id) rc ON t.tournament_id = rc.tournament_id " .
            "LEFT JOIN (SELECT tournament_id, COUNT(*) AS addonsPaid FROM poker_results WHERE result_paid_addon_flag = '" . Constant::FLAG_YES . "' AND result_place_finished = 0 GROUP BY tournament_id) ap ON t.tournament_id = ap.tournament_id " .
            "LEFT JOIN (SELECT tournament_id, COUNT(*) AS enteredCount FROM poker_results WHERE status_code = '" . Constant::CODE_STATUS_FINISHED . "' AND result_place_finished <> 0 GROUP BY tournament_id) ec ON t.tournament_id = ec.tournament_id " .
            "LEFT JOIN (SELECT tournament_id, COUNT(*) AS registeredCount FROM poker_results WHERE status_code = '" . Constant::CODE_STATUS_REGISTERED . "' GROUP BY tournament_id) nr ON t.tournament_id = nr.tournament_id";
        if (isset($tournamentDate) && isset($startTime)) {
            $sql .=
                " WHERE t.tournament_date >= :tournamentDate1 OR (t.tournament_date = :tournamentDate2 AND t.tournament_start_time >= :startTime)" .
                " ORDER BY t.tournament_date, t.tournament_start_time";
        }
        if (isset($tournamentId)) {
            $sql .= " WHERE t.tournament_id = :tournamentId";
        }
        if ($notEntered) {
            $sql .=
                " WHERE (CURRENT_DATE >= t.tournament_date OR t.tournament_date <= DATE_ADD(CURRENT_DATE, INTERVAL 28 DAY))" .
                " AND enteredCount IS NULL" .
                " ORDER BY t.tournament_date, t.tournament_start_time";
        }
        if ($ordered) {
            $sql .= " ORDER BY t.tournament_date DESC, t.tournament_start_time DESC";
        }
        if (isset($mode)) {
            if ($mode == Constant::MODE_CREATE) {
                $orderBy =
                    " WHERE enteredCount IS NULL" .
                    " AND buyinsPaid > 0" .
                    " AND t.tournament_id NOT IN (SELECT DISTINCT tournament_id FROM poker_results WHERE result_place_finished <> 0)";
            } else if ($mode == Constant::MODE_MODIFY) {
                $orderBy = " WHERE enteredCount > 0";
            }
            $orderBy .= " ORDER BY t.tournament_date DESC, t.tournament_start_time DESC";
            $sql .= $orderBy;
        }
        if (isset($interval)) {
            $sql .=
                " WHERE DATE(tournament_date) = DATE(DATE_ADD(now(), INTERVAL :interval DAY))" .
                " ORDER BY tournament_date, tournament_start_time";
        }
        if (isset($limitCount)) {
            $sql .= " LIMIT :limitCount";
        }
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        if (isset($tournamentDate) && isset($startTime)) {
            $tournamentDateFormatted = DateTimeUtility::formatDatabaseDate(value: $tournamentDate);
            $startTimeFormatted = DateTimeUtility::formatDatabaseTime(value: $startTime);
            $statement->bindValue("tournamentDate1", $tournamentDateFormatted, PDO::PARAM_STR);
            $statement->bindValue("tournamentDate2", $tournamentDateFormatted, PDO::PARAM_STR);
            $statement->bindValue("startTime", $startTimeFormatted, PDO::PARAM_STR);
        }
        if (isset($tournamentId)) {
            $statement->bindValue("tournamentId", $tournamentId, PDO::PARAM_INT);
        }
        if (isset($interval)) {
            $statement->bindValue("interval", $interval, PDO::PARAM_INT);
        }
        if (isset($limitCount)) {
            $statement->bindValue("limitCount", $limitCount, PDO::PARAM_INT);
        }
        return $statement->executeQuery()->fetchAllAssociative();
    }

    public function getRegistrationStatus(int $tournamentId, bool $indexed) {
//      case "tournamentSelectAllRegistrationStatus":
        $sql =
            "SELECT pt.player_id, CONCAT(pt.player_first_name, ' ', pt.player_last_name) AS name, " .
            "IF(pt.season_fee - IFNULL(pt.fee_amount, 0) = 0, 'Paid', CONCAT('Owes $', season_fee - IFNULL(pt.fee_amount, 0))) AS 'season fee', " .
            "IF(r.result_registration_order IS NULL, 'Not registered', 'Registered') AS status, " .
            "IF(r.result_registration_order IS NULL, 'N/A', IF(r.result_registration_order > pt.tournament_max_players, CONCAT(r.result_registration_order, ' (Wait list #', r.result_registration_order - pt.tournament_max_players, ')'), r.result_registration_order)) AS 'order' " .
            "FROM (SELECT p.player_id, p.player_first_name, p.player_last_name, t.tournament_id, t.tournament_max_players, s.season_fee, f.fee_amount " .
            "      FROM poker_players p CROSS JOIN poker_tournaments t ON p.player_active_flag = 1 AND t.tournament_id = :tournamentId" .
            "      LEFT JOIN poker_seasons s ON t.tournament_date BETWEEN s.season_start_date AND s.season_end_date " .
            "      LEFT JOIN (SELECT season_id, player_id, SUM(fee_amount) AS fee_amount FROM poker_fees GROUP BY season_id, player_id) f ON s.season_id = f.season_id AND p.player_id = f.player_id) pt " .
            "LEFT JOIN poker_results r ON pt.player_id = r.player_id AND r.tournament_id = pt.tournament_id AND r.status_code IN ('" . Constant::CODE_STATUS_REGISTERED . "', '" . Constant::CODE_STATUS_PAID . "');";
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->bindValue("tournamentId", $tournamentId, PDO::PARAM_STR);
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }

    public function getYears() {
//              case "tournamentSelectAllYearsPlayed":
        $qb = $this->createQueryBuilder("t");
        return $qb->select("DISTINCT YEAR(t.tournamentDate) AS year")
                  ->addOrderBy("YEAR(t.tournamentDate)", "DESC")
                  ->getQuery()->getResult();
    }

    public function getForIdAndDates(int $playerId, DateTime $startDate, DateTime $endDate) {
//         case "tournamentsPlayedByPlayerIdAndDateRange":
//         $sql =
//             "SELECT COUNT(*) AS numPlayed " .
//             "FROM poker_tournaments t INNER JOIN poker_results r ON t.tournament_id = r.tournament_id " .
//             "AND player_id = :playerId " .
//             "AND result_place_finished > 0 " .
//             "AND tournament_date BETWEEN :startDate AND :endDate";
        $qb = $this->createQueryBuilder("t");
        $startDateFormatted = DateTimeUtility::formatDatabaseDate(value: $startDate);
        $endDateFormatted = DateTimeUtility::formatDatabaseDate(value: $endDate);
        return $qb->select("COUNT(t) AS numPlayed")
                  ->innerJoin("t.results", "r")
                  ->where("r.players = :playerId")
                  ->andWhere("r.resultPlaceFinished > 0")
                  ->andWhere("t.tournamentDate BETWEEN :startDate AND :endDate")
                  ->setParameters(new ArrayCollection(array(new Parameter("playerId", $playerId), new Parameter("startDate", $startDateFormatted), new Parameter("endDate", $endDateFormatted))))
                  ->getQuery()->getResult();
    }

    public function getWon(int $playerId) {
//              case "tournamentsWonByPlayerId":
        $sql =
            "SELECT t.tournament_id AS id, t.tournament_description AS description, t.tournament_comment AS comment, t.location_id, l.location_name AS location, t.limit_type_id, lt.limit_type_name AS 'limit', t.game_type_id, gt.game_type_name AS 'type', t.tournament_chip_count AS 'chips', " .
            "l.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, l.location_address, l.location_city, l.location_state, l.location_zip_code, p.player_phone, t.tournament_date AS date, t.tournament_start_time AS 'start', t.tournament_buyin_amount AS 'buyin', t.tournament_max_players AS 'max players', t.tournament_max_rebuys AS 'max', t.tournament_rebuy_amount AS 'amt', t.tournament_addon_amount AS 'amt ', " .
            "t.tournament_addon_chip_count AS 'chips ', t.group_id, t.tournament_rake AS rake, l.location_map AS mapHide, l.location_map_link AS map, IFNULL(ec.enteredCount, 0) AS enteredCount, p.player_active_flag, t.special_type_id, st.special_type_description AS std, " .
            "(((t.tournament_buyin_amount * ec.enteredCount) + (IFNULL(nr.numRebuys, 0) * t.tournament_rebuy_amount) + (IFNULL(na.numAddons, 0) * t.tournament_addon_amount)) * .8)* s.structure_percentage AS earnings, st.special_type_multiplier " .
            "FROM poker_players p INNER JOIN poker_results r ON p.player_id = r.player_id AND p.player_id = :playerId " .
            "INNER JOIN poker_tournaments t ON t.tournament_id = r.tournament_id AND r.result_place_finished = 1 " .
            "LEFT JOIN poker_special_types st ON t.special_type_id = st.special_type_id " .
            "INNER JOIN poker_game_types gt ON t.game_type_id = gt.game_type_id " .
            "INNER JOIN poker_limit_types lt ON t.limit_type_id = lt.limit_type_id " .
            "INNER JOIN poker_locations l ON t.location_id = l.location_id " .
            "LEFT JOIN (SELECT tournament_id, COUNT(*) AS enteredCount " .
            "           FROM poker_results " .
            "           WHERE status_code = '" . Constant::CODE_STATUS_FINISHED . "' " .
            "           AND result_place_finished <> 0 " .
            "           GROUP BY tournament_id) ec ON t.tournament_id = ec.tournament_id " .
            "LEFT JOIN (SELECT a.tournament_id, s1.payout_id, s1.structure_place, s1.structure_percentage " .
            "          FROM (SELECT np.tournament_id, p.payout_id " .
            "                FROM (SELECT r.tournament_id, COUNT(*) AS numPlayers " .
            "                      FROM poker_results r " .
            "                      WHERE r.status_code IN ('" . Constant::CODE_STATUS_REGISTERED . "','" . Constant::CODE_STATUS_FINISHED . "') " .
            "                      GROUP BY r.tournament_id) np " .
            "                INNER JOIN poker_tournaments t on np.tournament_id = t.tournament_id " .
            "                INNER JOIN poker_group_payouts gp ON t.group_id = gp.group_id " .
            "                INNER JOIN poker_payouts p ON gp.payout_id = p.payout_id AND np.numPlayers BETWEEN p.payout_min_players AND p.payout_max_players) a " .
            "          INNER JOIN poker_structures s1 ON a.payout_id = s1.payout_id) s ON r.tournament_id = s.tournament_id AND r.result_place_finished = s.structure_place " .
            "LEFT JOIN (SELECT r2.tournament_id, SUM(r2.result_rebuy_count) AS numRebuys " .
            "           FROM poker_results r2 " .
            "           WHERE r2.result_rebuy_count > 0 GROUP BY r2.tournament_id) nr ON r.tournament_id = nr.tournament_id " .
            "LEFT JOIN (SELECT tournament_id, COUNT(result_paid_addon_flag) AS numAddons " .
            "           FROM poker_results WHERE result_paid_addon_flag = '" . Constant::FLAG_YES . "' GROUP BY tournament_id) na ON r.tournament_id = na.tournament_id " .
            "ORDER BY tournament_date, tournament_start_time";
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->bindValue("playerId", $playerId, PDO::PARAM_INT);
        return $statement->executeQuery()->fetchAllAssociative();
    }

    public function getPlayed(bool $indexed) {
//         case "tournamentsPlayed":
//             $query =
//             "SELECT p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, p.player_active_flag, COUNT(*) AS tourneys " .
//             "FROM poker_players p " .
//             "LEFT JOIN poker_results r ON p.player_id = r.player_id AND r.result_place_finished > 0 " .
//             "WHERE " . $this->buildPlayerActive(alias: "p") .
//             " GROUP BY p.player_id <REPLACE>";
//             if ($rank) {
//                 $whereClause = "<REPLACE>";
//                 $orderByFieldName = "tourneys DESC, " . $this->buildOrderByName(prefix: NULL);
//                 $selectFieldNames = "player_id, name, tourneys";
//                 $query = $this->modifyQueryAddRank(query: $query, whereClause: $whereClause, selectFieldName: "tourneys", selectFieldNames: $selectFieldNames, orderByFieldName: $orderByFieldName);
//             }
        $sql =
            "SELECT p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, p.player_active_flag, COUNT(*) AS tourneys " .
            "FROM poker_players p LEFT JOIN poker_results r ON p.player_id = r.player_id AND r.result_place_finished > 0 " .
            "WHERE p.player_active_flag = 1 " .
            "GROUP BY p.player_id <REPLACE>";
        $whereClause = "<REPLACE>";
        $orderByFieldName = "tourneys DESC, p.player_last_name, p.player_first_name";
        $selectFieldNames = "player_id, name, tourneys";
        $sql = $this->modifyQueryAddRank(query: $sql, whereClause: $whereClause, selectFieldName: "tourneys", selectFieldNames: $selectFieldNames, orderByFieldName: $orderByFieldName);
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }

    public function getPlayedByType(int $playerId, bool $indexed) {
//         case "tournamentsPlayedByType":
        $sql =
            "SELECT lt.limit_type_id, lt.limit_type_name AS 'limit type', gt.game_type_id, gt.game_type_name AS 'game type', t.tournament_max_rebuys AS rebuys, IF(t.tournament_addon_amount > 0, '" . Constant::FLAG_YES . "', '" . Constant::FLAG_NO . "') AS addon, COUNT(*) AS count " .
            "FROM poker_tournaments t " .
            "INNER JOIN poker_limit_types lt ON lt.limit_type_id = t.limit_type_id " .
            "INNER JOIN poker_game_types gt ON gt.game_type_id = t.game_type_id " .
            "INNER JOIN poker_results r ON t.tournament_id = r.tournament_id AND r.status_code = '" . Constant::CODE_STATUS_FINISHED . "' AND r.player_id = :playerId " .
            "GROUP BY lt.limit_type_id, lt.limit_type_name, gt.game_type_id, gt.game_type_name, t.tournament_max_rebuys, addon";
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->bindValue("playerId", $playerId, PDO::PARAM_INT);
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
        //         $qb = $this->createQueryBuilder("t");
//         return $qb->select("t, lt, gt, COUNT(t) AS numPlayed")
//                   ->innerJoin("t.results", "r")
//                   ->innerJoin("t.limitTypes", "lt")
//                   ->innerJoin("t.gameTypes", "gt")
//                   ->where("r.players = :playerId")
//                   ->andWhere("r.statusCodes = '" . Constant::CODE_STATUS_FINISHED . "'")
//                   ->addGroupBy("lt.limitTypeName")
//                   ->addGroupBy("gt.gameTypeName")
//                   ->addGroupBy("t.tournamentMaxRebuys")
//                   ->setParameters(new ArrayCollection(array(new Parameter("playerId", $playerId))))
//                   ->getQuery()->getResult();
    }

    public function getResultsMinDate(int $playerId) { // min with results
//         case "tournamentsPlayedFirst":
//             $query =
//             "SELECT MIN(t.tournament_date) AS date " .
//             "FROM poker_tournaments t " .
//             "INNER JOIN poker_results r ON t.tournament_id = r.tournament_id AND r.player_id = :playerId";
//         $statement = $this->getEntityManager()->getConnection()->prepare($sql);
//         $statement->bindValue("playerId", $playerId, PDO::PARAM_INT);
//         return $statement->executeQuery()->fetchAllAssociative();
        $qb = $this->createQueryBuilder("t");
        return $qb->select("MIN(t.tournamentDate) AS tournamentDate")
                  ->innerJoin("t.results", "r")
                  ->where("r.players = :playerId")
        ->setParameters(new ArrayCollection(array(new Parameter("playerId", $playerId))))
        ->getQuery()->getResult();
    }

    public function getOrderedDateTime() {
//                 case "tournamentIdList":
        $qb = $this->createQueryBuilder("t");
        return $qb->addOrderBy("t.tournamentDate")
                  ->addOrderBy("t.tournamentStartTime")
                  ->getQuery()->getResult();
    }

    public function getTableOutput(?int $tournamentId, bool $indexed) {
        $sql =
            "SELECT t.tournament_id AS id, t.tournament_description AS description, t.tournament_comment AS comment, t.location_id, l.location_name AS location, t.limit_type_id, lt.limit_type_name AS 'limit', t.game_type_id, gt.game_type_name AS 'type', " .
            "       l.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS playerName, l.location_address, l.location_city, l.location_state, l.location_zip_code, p.player_phone, l.location_map AS mapHide, l.location_map_link AS map, t.tournament_date AS date, t.tournament_start_time AS 'start', t.tournament_max_players AS 'max players', t.tournament_chip_count AS 'chips', -t.tournament_buyin_amount AS 'buyin', t.tournament_max_rebuys AS 'max', -t.tournament_rebuy_amount AS 'amt', -t.tournament_addon_amount AS 'amt ', " .
            "       t.tournament_addon_chip_count AS 'chips ', t.group_id, g.group_name AS name, t.tournament_rake AS rake, IFNULL(nr.registeredCount, 0) AS registeredCount, " .
            "       IFNULL(bp.buyinsPaid, 0) AS buyinsPaid, " .
            "       IFNULL(rp.rebuysPaid, 0) AS rebuysPaid, " .
            "       IFNULL(rc.rebuysCount, 0) AS rebuysCount, " .
            "       IFNULL(ap.addonsPaid, 0) AS addonsPaid, " .
            "       IFNULL(ec.enteredCount, 0) AS enteredCount, " .
            "       t.special_type_id, st.special_type_description AS std, st.special_type_multiplier " .
            "FROM poker_tournaments t INNER JOIN poker_game_types gt ON t.game_type_id = gt.game_type_id " .
            "INNER JOIN poker_limit_types lt ON t.limit_type_id = lt.limit_type_id " .
            "LEFT JOIN poker_special_types st ON t.special_type_id = st.special_type_id " .
            "INNER JOIN poker_locations l ON t.location_id = l.location_id " .
            "INNER JOIN poker_players p ON l.player_id = p.player_id " .
            "INNER JOIN poker_groups g on t.group_id = g.group_id " .
            "LEFT JOIN (SELECT tournament_id, COUNT(*) AS buyinsPaid FROM poker_results WHERE result_paid_buyin_flag = '" . Constant::FLAG_YES . "' AND result_place_finished = 0 GROUP BY tournament_id) bp ON t.tournament_id = bp.tournament_id " .
            "LEFT JOIN (SELECT tournament_id, COUNT(*) AS rebuysPaid FROM poker_results WHERE result_paid_rebuy_flag = '" . Constant::FLAG_YES . "' AND result_place_finished = 0 GROUP BY tournament_id) rp ON t.tournament_id = rp.tournament_id " .
            "LEFT JOIN (SELECT tournament_id, SUM(result_rebuy_count) AS rebuysCount FROM poker_results WHERE result_paid_rebuy_flag = '" . Constant::FLAG_YES . "' AND result_place_finished = 0 GROUP BY tournament_id) rc ON t.tournament_id = rc.tournament_id " .
            "LEFT JOIN (SELECT tournament_id, COUNT(*) AS addonsPaid FROM poker_results WHERE result_paid_addon_flag = '" . Constant::FLAG_YES . "' AND result_place_finished = 0 GROUP BY tournament_id) ap ON t.tournament_id = ap.tournament_id " .
            "LEFT JOIN (SELECT tournament_id, COUNT(*) AS enteredCount FROM poker_results WHERE status_code = '" . Constant::CODE_STATUS_FINISHED . "' AND result_place_finished <> 0 GROUP BY tournament_id) ec ON t.tournament_id = ec.tournament_id " .
            "LEFT JOIN (SELECT tournament_id, COUNT(*) AS registeredCount FROM poker_results WHERE status_code = '" . Constant::CODE_STATUS_REGISTERED . "' GROUP BY tournament_id) nr ON t.tournament_id = nr.tournament_id";
        if (isset($tournamentId)) {
            $sql .= " WHERE t.tournament_id = :tournamentId";
        }
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        if (isset($tournamentId)) {
            $statement->bindValue("tournamentId", $tournamentId, PDO::PARAM_INT);
        }
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }

    public function getMinDateTime(int $seasonId) {
        $sql =
            "SELECT MIN(t.tournament_date) AS tournamentDate, MIN(t.tournament_start_time) AS tournamentStartTime " .
            "FROM poker_tournaments t INNER JOIN poker_seasons s ON t.tournament_date BETWEEN s.season_start_date AND s.season_end_date " .
            "WHERE s.season_id = :seasonId";
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->bindValue("seasonId", $seasonId, PDO::PARAM_INT);
        return $statement->executeQuery()->fetchAllAssociative();
    }

    public function getForDateAndTime(DateTime $tournamentDate, DateTime $time) {
        $qb = $this->createQueryBuilder("t");
        $tournamentDateFormatted = DateTimeUtility::formatDatabaseDate(value: $tournamentDate);
        $timeFormatted = DateTimeUtility::formatDatabaseTime(value: $time);
        return $qb->where("t.tournamentDate = :tournamentDate")
                  ->andWhere("t.tournamentStartTime = :startTime")
                  ->setParameters(new ArrayCollection(array(new Parameter("tournamentDate", $tournamentDateFormatted), new Parameter("startTime", $timeFormatted))))
                  ->getQuery()->getResult();
    }
}