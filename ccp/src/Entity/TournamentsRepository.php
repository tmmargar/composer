<?php
namespace Poker\Ccp\Entity;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\Parameter;
use PDO;
use Poker\Ccp\Model\Constant;
use Poker\Ccp\Utility\DateTimeUtility;
class TournamentsRepository extends BaseRepository {
    public function getById(?int $tournamentId) {
        $qb = $this->createQueryBuilder(alias: "t")
                   ->addSelect(select: "t, lt, gt, st, l, gr")
                   ->innerJoin(join: "t.limitTypes", alias: "lt")
                   ->innerJoin(join: "t.gameTypes", alias: "gt")
                   ->leftJoin(join: "t.specialTypes", alias: "st")
                   ->innerJoin(join: "t.locations", alias: "l")
                   ->innerJoin(join: "t.groups", alias: "gr")
                   ;
        if (isset($tournamentId)) {
            $qb = $qb->where(predicates: "t.tournamentId = :tournamentId")
                     ->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "tournamentId", value: $tournamentId))));
        }
        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getRegisterHost(DateTime $startDate, DateTime $endDate) {
        $qb = $this->createQueryBuilder(alias: "t");
        $dtStartFormatted = DateTimeUtility::formatDatabaseDate(value: $startDate);
        $dtEndFormatted = DateTimeUtility::formatDatabaseDate(value: $endDate);
        return $qb->innerJoin(join: "t.locations", alias: "l", conditionType: Expr\Join::WITH, condition: $qb->expr()->between("t.tournamentDate", ":startDate", "DATE_ADD(:endDate, 14, 'DAY')"))
                  ->innerJoin(join: "l.players", alias: "p", conditionType: Expr\Join::WITH, condition: "p.playerActiveFlag = " . Constant::FLAG_YES_DATABASE)
                  ->leftJoin(join: "t.results", alias: "r", conditionType: Expr\Join::WITH, condition: "p.playerId = r.players")
                  ->where(predicates: "r.players IS NULL")
                  ->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "startDate", value: $dtStartFormatted), new Parameter(name: "endDate", value: $dtEndFormatted))))
                  ->getQuery()->getResult();
    }

    public function getCountForDates(DateTime $startDate, DateTime $endDate) {
        $startDateFormatted = DateTimeUtility::formatDatabaseDate(value: $startDate);
        $endDateFormatted = DateTimeUtility::formatDatabaseDate(value: $endDate);
        return $this->createQueryBuilder(alias: "t")
                    ->where(predicates: "t.tournamentDate BETWEEN :startDate AND :endDate")
                    ->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "startDate", value: $startDateFormatted), new Parameter(name: "endDate", value: $endDateFormatted))))
                    ->getQuery()->getResult();
    }

    public function getCountForUserAndDates(int $playerId, DateTime $startDate, DateTime $endDate) {
        $dtStartFormatted = DateTimeUtility::formatDatabaseDate(value: $startDate);
        $dtEndFormatted = DateTimeUtility::formatDatabaseDate(value: $endDate);
        return $this->createQueryBuilder(alias: "t")
                    ->innerJoin(join: "t.results", alias: "r")
                    ->where(predicates: "r.players = :playerId")
                    ->andWhere("r.statusCodes = :statusCode")
                    ->andWhere("t.tournamentDate BETWEEN :startDate AND :endDate")
                    ->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "playerId", value: $playerId), new Parameter(name: "statusCode", value: Constant::CODE_STATUS_FINISHED), new Parameter(name: "startDate", value: $dtStartFormatted), new Parameter(name: "endDate", value: $dtEndFormatted))))
                    ->getQuery()->getResult();
    }

    public function getForYear(DateTime $tournamentDate) {
        return $this->createQueryBuilder(alias: "t")
                    ->select(select: "t")
                    ->where(predicates: "YEAR(t.tournamentDate) = :tournamentDate")
                    ->addOrderBy("t.tournamentDate", "DESC")
                    ->addOrderBy("t.tournamentStartTime", "DESC")
                    ->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "tournamentDate", value: $tournamentDate))))
                    ->getQuery()->getResult();
    }
    public function getMaxId(int $seasonId) {
        $sql=
            "SELECT MAX(t.tournament_id) AS tournamentId " .
            "FROM poker_tournaments t INNER JOIN poker_seasons s ON t.tournament_date BETWEEN s.season_start_date AND s.season_end_date " .
            "WHERE s.season_id = :seasonId";
        $statement = $this->getEntityManager()->getConnection()->prepare(sql: $sql);
        $statement->bindValue(param: "seasonId", value: $seasonId, type: PDO::PARAM_INT);
        return $statement->executeQuery()->fetchAllNumeric();
    }

    public function getPrizePool(DateTime $startDate, DateTime $endDate) {
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
        $statement = $this->getEntityManager()->getConnection()->prepare(sql: $sql);
        if (isset($startDate) && isset($endDate)) {
            $startDateFormatted = DateTimeUtility::formatDatabaseDate(value: $startDate);
            $endDateFormatted = DateTimeUtility::formatDatabaseDate(value: $endDate);
            $statement->bindValue(param: "startDate", value: $startDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "endDate", value: $endDateFormatted, type: PDO::PARAM_STR);
        }
        return $statement->executeQuery()->fetchAllAssociative();
    }

    public function getRegistrationList(DateTime $tournamentDate, bool $max) {
        $sql =
            "SELECT p.player_first_name, p.player_last_name, r.result_registration_food, " .
            "IF(s.season_fee IS NULL, '', IF(s.season_fee - IFNULL(f.fee_amount, 0) - IF(fh.player_id IS NULL, 0, s.season_fee) = 0, 'Paid', CONCAT('Owes $', (s.season_fee - IFNULL(f.fee_amount, 0))))) AS 'fee status' " .
            "FROM (SELECT t1.tournament_id, t1.tournament_date " .
            "      FROM poker_tournaments t1 INNER JOIN (SELECT tournament_date, MIN(tournament_start_time) startTimeMin, MAX(tournament_start_time) startTimeMax " .
            "                                           FROM poker_tournaments " .
            "                                           WHERE tournament_date = :tournamentDate) t2 " .
            "      ON t1.tournament_date = t2.tournament_date " .
            "      AND t1.tournament_start_time = t2.startTime" . ($max ? "Max" : "Min") . ") t INNER JOIN poker_results r ON t.tournament_id = r.tournament_id " .
            "INNER JOIN poker_players p ON r.player_id = p.player_id " .
            "INNER JOIN poker_seasons s ON t.tournament_date BETWEEN s.season_start_date AND s.season_end_date " .
            "LEFT JOIN (SELECT season_id, player_id, SUM(fee_amount) AS fee_amount FROM poker_fees GROUP BY season_id, player_id) f ON s.season_id = f.season_id AND p.player_id = f.player_id " .
            "LEFT JOIN (SELECT DISTINCT se.season_id, l.location_id, l.location_name, l.player_id, p.player_first_name, p.player_last_name " .
            "           FROM poker_tournaments t INNER JOIN poker_seasons se ON t.tournament_date BETWEEN se.season_start_date AND se.season_end_date " .
            "           INNER JOIN poker_locations l ON t.location_id = l.location_id " .
            "           INNER JOIN poker_players p ON l.player_id = p.player_id " .
            "           GROUP BY se.season_id, l.location_id) fh ON s.season_id = fh.season_id AND p.player_id = fh.player_id " .
            "ORDER BY r.result_registration_order";
        $statement = $this->getEntityManager()->getConnection()->prepare(sql: $sql);
        if (isset($tournamentDate)) {
            $tournamentDateFormatted = DateTimeUtility::formatDatabaseDate(value: $tournamentDate);
            $statement->bindValue(param: "tournamentDate", value: $tournamentDateFormatted, type: PDO::PARAM_STR);
        }
        return $statement->executeQuery()->fetchAllAssociative();
    }

    public function getRegistrationWaitList(int $tournamentId, int $registerOrder) {
        return $this->createQueryBuilder(alias: "t")
                    ->addSelect(select: "r")
                    ->innerJoin(join: "t.results", alias: "r")
                    ->where(predicates: "t.tournamentId = :tournamentId")
                    ->andWhere("r.resultRegistrationOrder > :registerOrder")
                    ->andWhere("r.resultRegistrationOrder > t.tournamentMaxPlayers")
                    ->addOrderBy(sort: "r.resultRegistrationOrder", order: "ASC")
                    ->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "tournamentId", value: $tournamentId), new Parameter(name: "registerOrder", value: $registerOrder))))
                    ->getQuery()->getResult();
    }

    public function getResultsMaxId(DateTime $tournamentDate) { // max with results
        $tournamentDateFormatted = DateTimeUtility::formatDatabaseDate(value: $tournamentDate);
        return $this->createQueryBuilder(alias: "t")
                    ->select(select: "MAX(t.tournamentId) AS tournamentId")
                    ->innerJoin(join: "t.results", alias: "r")
                    ->where(predicates: "t.tournamentDate <= :tournamentDate")
                    ->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "tournamentDate", value: $tournamentDateFormatted))))
                    ->getQuery()->getResult();
    }

    public function getAllMultiple(bool $championship, ?DateTime $tournamentDate, ?DateTime $startTime, ?int $tournamentId, bool $notEntered, bool $ordered, ?string $mode, ?int $interval, ?int $limitCount) {
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
        $statement = $this->getEntityManager()->getConnection()->prepare(sql: $sql);
        if (isset($tournamentDate) && isset($startTime)) {
            $tournamentDateFormatted = DateTimeUtility::formatDatabaseDate(value: $tournamentDate);
            $startTimeFormatted = DateTimeUtility::formatDatabaseTime(value: $startTime);
            $statement->bindValue(param: "tournamentDate1", value: $tournamentDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "tournamentDate2", value: $tournamentDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "startTime", value: $startTimeFormatted, type: PDO::PARAM_STR);
        }
        if (isset($tournamentId)) {
            $statement->bindValue(param: "tournamentId", value: $tournamentId, type: PDO::PARAM_INT);
        }
        if (isset($interval)) {
            $statement->bindValue(param: "interval", value: $interval, type: PDO::PARAM_INT);
        }
        if (isset($limitCount)) {
            $statement->bindValue(param: "limitCount", value: $limitCount, type: PDO::PARAM_INT);
        }
        return $statement->executeQuery()->fetchAllAssociative();
    }

    public function getRegistrationStatus(int $tournamentId, bool $indexed) {
        $sql =
            "SELECT pt.player_id, CONCAT(pt.player_first_name, ' ', pt.player_last_name) AS name, " .
            "IF(pt.season_fee - IFNULL(pt.fee_amount, 0) - IF(fh.player_id IS NULL, 0, pt.season_fee) = 0, 'Paid', CONCAT('Owes $', season_fee - IFNULL(pt.fee_amount, 0))) AS 'season fee', " .
            "IF(r.result_registration_order IS NULL, 'Not registered', 'Registered') AS status, " .
            "IF(r.result_registration_order IS NULL, 'N/A', IF(r.result_registration_order > pt.tournament_max_players, CONCAT(r.result_registration_order, ' (Wait list #', r.result_registration_order - pt.tournament_max_players, ')'), r.result_registration_order)) AS 'order' " .
            "FROM (SELECT p.player_id, p.player_first_name, p.player_last_name, t.tournament_id, t.tournament_max_players, s.season_fee, f.fee_amount, s.season_id " .
            "      FROM poker_players p CROSS JOIN poker_tournaments t ON p.player_active_flag = " . Constant::FLAG_YES_DATABASE . " AND t.tournament_id = :tournamentId" .
            "      LEFT JOIN poker_seasons s ON t.tournament_date BETWEEN s.season_start_date AND s.season_end_date " .
            "      LEFT JOIN (SELECT season_id, player_id, SUM(fee_amount) AS fee_amount FROM poker_fees GROUP BY season_id, player_id) f ON s.season_id = f.season_id AND p.player_id = f.player_id) pt " .
            "LEFT JOIN poker_results r ON pt.player_id = r.player_id AND r.tournament_id = pt.tournament_id AND r.status_code IN ('" . Constant::CODE_STATUS_REGISTERED . "', '" . Constant::CODE_STATUS_PAID . "')" .
            "LEFT JOIN (SELECT DISTINCT se.season_id, l.location_id, l.location_name, l.player_id, p.player_first_name, p.player_last_name " .
            "           FROM poker_tournaments t INNER JOIN poker_seasons se ON t.tournament_date BETWEEN se.season_start_date AND se.season_end_date " .
            "           INNER JOIN poker_locations l ON t.location_id = l.location_id " .
            "           INNER JOIN poker_players p ON l.player_id = p.player_id " .
            "           GROUP BY se.season_id, l.location_id) fh ON pt.season_id = fh.season_id AND pt.player_id = fh.player_id";
        $statement = $this->getEntityManager()->getConnection()->prepare(sql: $sql);
        $statement->bindValue(param: "tournamentId", value: $tournamentId, type: PDO::PARAM_STR);
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }

    public function getYears() {
        return $this->createQueryBuilder(alias: "t")
                    ->select(select: "DISTINCT YEAR(t.tournamentDate) AS year")
                    ->addOrderBy(sort: "YEAR(t.tournamentDate)", order: "DESC")
                    ->getQuery()->getResult();
    }

    public function getForIdAndDates(int $playerId, DateTime $startDate, DateTime $endDate) {
        $startDateFormatted = DateTimeUtility::formatDatabaseDate(value: $startDate);
        $endDateFormatted = DateTimeUtility::formatDatabaseDate(value: $endDate);
        return $this->createQueryBuilder(alias: "t")
                    ->select(select: "COUNT(t) AS numPlayed")
                    ->innerJoin(join: "t.results", alias: "r")
                    ->where(predicates: "r.players = :playerId")
                    ->andWhere("r.resultPlaceFinished > 0")
                    ->andWhere("t.tournamentDate BETWEEN :startDate AND :endDate")
                    ->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "playerId", value: $playerId), new Parameter(name: "startDate", value: $startDateFormatted), new Parameter(name: "endDate", value: $endDateFormatted))))
                    ->getQuery()->getResult();
    }

    public function getWon(int $playerId) {
        $sql =
            "SELECT t.tournament_id AS id, t.tournament_description AS description, t.tournament_comment AS comment, t.location_id, l.location_name AS location, t.limit_type_id, lt.limit_type_name AS 'limit', t.game_type_id, gt.game_type_name AS 'type', t.tournament_chip_count AS 'chips', " .
            "l.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, l.location_address, l.location_city, l.location_state, l.location_zip_code, p.player_phone, t.tournament_date AS date, t.tournament_start_time AS 'start', t.tournament_buyin_amount AS 'buyin', t.tournament_max_players AS 'max players', t.tournament_max_rebuys AS 'max', t.tournament_rebuy_amount AS 'amt', t.tournament_addon_amount AS 'amt ', " .
            "t.tournament_addon_chip_count AS 'chips ', t.group_id, t.tournament_rake AS rake, l.location_map AS mapHide, l.location_map_link AS map, IFNULL(ec.enteredCount, 0) AS enteredCount, p.player_active_flag, t.special_type_id, st.special_type_description AS std, " .
            "(((t.tournament_buyin_amount * ec.enteredCount) + (IFNULL(nr.numRebuys, 0) * t.tournament_rebuy_amount) + (IFNULL(na.numAddons, 0) * t.tournament_addon_amount)) * .8)* s.structure_percentage AS earnings, st.special_type_multiplier " .
            "FROM poker_players p INNER JOIN poker_results r ON p.player_id = r.player_id AND p.player_id = :playerId " .
            "INNER JOIN poker_tournaments t ON t.tournament_id = r.tournament_id AND r.result_place_finished = " . Constant::FLAG_YES_DATABASE .
            " LEFT JOIN poker_special_types st ON t.special_type_id = st.special_type_id " .
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
        $statement = $this->getEntityManager()->getConnection()->prepare(sql: $sql);
        $statement->bindValue(param: "playerId", value: $playerId, type: PDO::PARAM_INT);
        return $statement->executeQuery()->fetchAllAssociative();
    }

    public function getPlayed(bool $indexed) {
        $sql =
            "SELECT p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, p.player_active_flag, COUNT(*) AS tourneys " .
            "FROM poker_players p LEFT JOIN poker_results r ON p.player_id = r.player_id AND r.result_place_finished > 0 " .
            "WHERE p.player_active_flag = " . Constant::FLAG_YES_DATABASE .
            " GROUP BY p.player_id <REPLACE>";
        $whereClause = "<REPLACE>";
        $orderByFieldName = "tourneys DESC, p.player_last_name, p.player_first_name";
        $selectFieldNames = "player_id, name, tourneys";
        $sql = $this->modifyQueryAddRank(query: $sql, whereClause: $whereClause, selectFieldName: "tourneys", selectFieldNames: $selectFieldNames, orderByFieldName: $orderByFieldName);
        $statement = $this->getEntityManager()->getConnection()->prepare(sql: $sql);
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }

    public function getPlayedByType(int $playerId, bool $indexed) {
        $sql =
            "SELECT lt.limit_type_id, lt.limit_type_name AS 'limit type', gt.game_type_id, gt.game_type_name AS 'game type', t.tournament_max_rebuys AS rebuys, IF(t.tournament_addon_amount > 0, '" . Constant::FLAG_YES . "', '" . Constant::FLAG_NO . "') AS addon, COUNT(*) AS count " .
            "FROM poker_tournaments t " .
            "INNER JOIN poker_limit_types lt ON lt.limit_type_id = t.limit_type_id " .
            "INNER JOIN poker_game_types gt ON gt.game_type_id = t.game_type_id " .
            "INNER JOIN poker_results r ON t.tournament_id = r.tournament_id AND r.status_code = '" . Constant::CODE_STATUS_FINISHED . "' AND r.player_id = :playerId " .
            "GROUP BY lt.limit_type_id, lt.limit_type_name, gt.game_type_id, gt.game_type_name, t.tournament_max_rebuys, addon";
        $statement = $this->getEntityManager()->getConnection()->prepare(sql: $sql);
        $statement->bindValue(param: "playerId", value: $playerId, type: PDO::PARAM_INT);
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }

    public function getResultsMinDate(int $playerId) { // min with results
        return $this->createQueryBuilder(alias: "t")
                    ->select(select: "MIN(t.tournamentDate) AS tournamentDate")
                    ->innerJoin(join: "t.results", alias: "r")
                    ->where(predicates: "r.players = :playerId")
                    ->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "playerId", value: $playerId))))
                    ->getQuery()->getResult();
    }

    public function getOrderedDateTime() {
        return $this->createQueryBuilder(alias: "t")
                    ->addOrderBy(sort: "t.tournamentDate")
                    ->addOrderBy(sort: "t.tournamentStartTime")
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
        $statement = $this->getEntityManager()->getConnection()->prepare(sql: $sql);
        if (isset($tournamentId)) {
            $statement->bindValue(param: "tournamentId", value: $tournamentId, type: PDO::PARAM_INT);
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
        $statement = $this->getEntityManager()->getConnection()->prepare(sql: $sql);
        $statement->bindValue(param: "seasonId", value: $seasonId, type: PDO::PARAM_INT);
        return $statement->executeQuery()->fetchAllAssociative();
    }

    public function getForDateAndTime(DateTime $tournamentDate, DateTime $time) {
        $tournamentDateFormatted = DateTimeUtility::formatDatabaseDate(value: $tournamentDate);
        $timeFormatted = DateTimeUtility::formatDatabaseTime(value: $time);
        return $this->createQueryBuilder(alias: "t")
                    ->where(predicates: "t.tournamentDate = :tournamentDate")
                    ->andWhere("t.tournamentStartTime = :startTime")
                    ->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "tournamentDate", value: $tournamentDateFormatted), new Parameter(name: "startTime", value: $timeFormatted))))
                    ->getQuery()->getResult();
    }

    public function getChampionshipPayout() {
        $sql =
            "SELECT t.tournament_id, t.group_id, gp.payout_id " .
            "FROM poker_tournaments t cross join poker_seasons se ON se.season_active_flag = " . Constant::FLAG_YES_DATABASE .
            " INNER JOIN poker_group_payouts gp ON t.group_id = gp.group_id " .
            "LEFT JOIN poker_special_types st ON t.special_type_id = st.special_type_id " .
            "WHERE st.special_type_description = '" . Constant::DESCRIPTION_CHAMPIONSHIP . "' " .
            "AND t.tournament_date BETWEEN se.season_start_date AND se.season_end_date";
        $statement = $this->getEntityManager()->getConnection()->prepare(sql: $sql);
        return $statement->executeQuery()->fetchAllAssociative();
    }
}