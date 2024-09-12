<?php
namespace Poker\Ccp\Entity;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use PDO;
use Poker\Ccp\Model\Constant;
use Poker\Ccp\Utility\DateTimeUtility;
class ResultsRepository extends BaseRepository {
    public function getBullies(int $knockedOutBy, ?int $limitCount, bool $indexed) {
        $sql =
            "SELECT CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, p.player_active_flag, COUNT(r.player_id) AS kos " .
            "FROM poker_results r " .
            "INNER JOIN poker_players p ON r.player_id = p.player_id " .
            "WHERE p.player_active_flag = " . Constant::FLAG_YES_DATABASE .
            " AND r.player_id_ko = :knockedOutBy " .
            "GROUP BY r.player_id " .
            "ORDER BY kOs DESC, p.player_last_name, p.player_first_name";
        if (isset($limitCount)) {
            $sql .= " LIMIT :limitCount";
        }
        $statement = $this->getEntityManager()->getConnection()->prepare(sql: $sql);
        $statement->bindValue(param: "knockedOutBy", value: $knockedOutBy, type: PDO::PARAM_INT);
        if (isset($limitCount)) {
            $statement->bindValue(param: "limitCount", value: $limitCount, type: PDO::PARAM_INT);
        }
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }

    public function getNemesises(int $playerId, ?int $limitCount, bool $indexed) {
        $sql =
             "SELECT CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, p.player_active_flag, COUNT(r.player_id_ko) AS kos " .
             "FROM poker_results r INNER JOIN poker_players p ON r.player_id_ko = p.player_id " .
             "WHERE p.player_active_flag = " . Constant::FLAG_YES_DATABASE .
             " AND r.player_id = :playerId " .
             "GROUP BY r.player_id_ko " .
             "ORDER BY kOs DESC, p.player_last_name, p.player_first_name";
        if (isset($limitCount)) {
            $sql .= " LIMIT :limitCount";
        }
        $statement = $this->getEntityManager()->getConnection()->prepare(sql: $sql);
        $statement->bindValue(param: "playerId", value: $playerId, type: PDO::PARAM_INT);
        if (isset($limitCount)) {
            $statement->bindValue(param: "limitCount", value: $limitCount, type: PDO::PARAM_INT);
        }
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }

    public function getPaidOrRegisteredOrFinished(?int $tournamentId, ?int $playerId, bool $registered, bool $finished, bool $paid, bool $indexed) {
        $sql = "SELECT r.tournament_id, r.player_id, CONCAT(p.player_first_name, ' ' , p.player_last_name) AS name, p.player_email, r.status_code, s.status_code_name AS status, r.result_registration_order, r.result_paid_buyin_flag, r.result_paid_rebuy_flag, r.result_rebuy_count AS rebuy, r.result_paid_addon_flag AS addon, r.result_addon_flag, r.result_place_finished AS place, r.player_id_ko, CONCAT(p2.player_first_name, ' ' , p2.player_last_name) AS 'knocked out by', r.result_registration_food, p.player_active_flag, p2.player_active_flag AS knockedOutActive, " .
                "IF(se.season_fee IS NULL, '', IF(se.season_fee - IFNULL(f.fee_amount, 0) - IF(fh.player_id IS NULL, 0, se.season_fee) = 0, 'Paid', CONCAT('Owes $', (se.season_fee - IFNULL(f.fee_amount, 0))))) AS feeStatus " .
                "FROM poker_results r INNER JOIN poker_players p ON r.player_id = p.player_id " .
                "LEFT JOIN poker_players p2 ON r.player_id_ko = p2.player_id " .
                "INNER JOIN poker_status_codes s ON r.status_code = s.status_code " .
                "INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id " .
                "INNER JOIN poker_seasons se ON t.tournament_date BETWEEN se.season_start_date AND se.season_end_date " .
                "LEFT JOIN (SELECT season_id, player_id, SUM(fee_amount) AS fee_amount FROM poker_fees GROUP BY season_id, player_id) f ON se.season_id = f.season_id AND p.player_id = f.player_id " .
                "LEFT JOIN (SELECT DISTINCT se.season_id, l.location_id, l.location_name, l.player_id, p.player_first_name, p.player_last_name " .
                "           FROM poker_tournaments t INNER JOIN poker_seasons se ON t.tournament_date BETWEEN se.season_start_date AND se.season_end_date " .
                "           INNER JOIN poker_locations l ON t.location_id = l.location_id " .
                "           INNER JOIN poker_players p ON l.player_id = p.player_id " .
                "           GROUP BY se.season_id, l.location_id) fh ON se.season_id = fh.season_id AND p.player_id = fh.player_id";
        if (isset($tournamentId) && isset($playerId)) {
            $sql .=
                " WHERE r.tournament_id = :tournamentId" .
                " AND r.player_id = :playerId";
        } else if ($registered) {
            $sql .=
                " WHERE r.tournament_id = :tournamentId" .
                " AND r.status_code IN ('" . Constant::CODE_STATUS_REGISTERED . "', '" . Constant::CODE_STATUS_PAID . "')" .
                " AND r.result_place_finished = 0" .
                " ORDER BY r.result_registration_order";
        } else if ($finished) {
            $sql .=
                " WHERE r.tournament_id = :tournamentId" .
                " AND r.result_place_finished <> 0" .
                " ORDER BY r.result_place_finished DESC";
        } else if ($paid) {
            $sql .=
                " WHERE r.tournament_id = :tournamentId" .
                " AND r.result_paid_buyin_flag = '" . Constant::FLAG_YES . "'" .
                " ORDER BY p.player_last_name, p.player_first_name";
        }
        $statement = $this->getEntityManager()->getConnection()->prepare(sql: $sql);
        if (isset($tournamentId) && isset($playerId)) {
            $statement->bindValue(param: "tournamentId", value: $tournamentId, type: PDO::PARAM_INT);
            $statement->bindValue(param: "playerId", value: $playerId, type: PDO::PARAM_INT);
        }
        if ($registered || $finished || $paid) {
            $statement->bindValue(param: "tournamentId", value: $tournamentId, type: PDO::PARAM_INT);
        }
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }

    public function getForTournament(?int $prizePool, int $tournamentId, bool $indexed) {
        if (isset($prizePool)) {
            $temp = $prizePool;
        } else {
            $temp =
                "((np.NumPlayers * (t.tournament_buyin_amount - (t.tournament_buyin_Amount * t.tournament_rake))) + " .
                " (IFNULL(nr.NumRebuys, 0) * (t.tournament_rebuy_amount - (t.tournament_rebuy_amount * t.tournament_rake))) + " .
                " (IFNULL(na.NumAddons, 0) * (t.tournament_addon_amount - (t.tournament_addon_amount * t.tournament_rake))))";
        }
        $sql =
            "SELECT r.result_place_finished AS place, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, r.result_rebuy_count * t.tournament_rebuy_amount AS rebuy, " .
            "       IF(r.result_paid_addon_flag = '" . Constant::FLAG_YES . "', t.tournament_addon_amount, 0) AS addon, " .
            "       " . $temp . " * IFNULL(s.structure_percentage, 0) AS earnings, " .
            "       (np.numPlayers - r.result_place_finished + 1) * IFNULL(st.special_type_multiplier, 1) + IF(r.result_place_finished BETWEEN 1 AND se.season_final_table_players, se.season_final_table_bonus_points, 0) AS 'pts', " .
            "       ko.knockedOutBy AS 'knocked out by', p.player_active_flag, ko.player_active_flag AS knockOutActive " .
            "FROM poker_results r INNER JOIN poker_players p ON r.player_id = p.player_id " .
            "INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id " .
            "INNER JOIN poker_seasons se ON t.tournament_date BETWEEN se.season_start_date AND se.season_end_date " .
            "LEFT JOIN poker_special_types st ON t.special_type_id = st.special_type_id " .
            "INNER JOIN poker_locations l ON t.location_id = l.location_id " .
            "INNER JOIN poker_limit_types lt ON t.limit_type_id = lt.limit_type_id " .
            "INNER JOIN poker_game_types gt ON t.game_type_id = gt.game_type_id " .
            "LEFT JOIN " .
            "    (SELECT s1.payout_id, s1.structure_place, s1.structure_percentage " .
            "     FROM (SELECT p.payout_id " .
            "           FROM (SELECT r.tournament_id, COUNT(*) AS numPlayers " .
            "                 FROM poker_results r " .
            "                 WHERE r.tournament_id = :tournamentId1 " .
            " AND r.result_place_finished > 0 AND r.status_code IN ('" . Constant::CODE_STATUS_REGISTERED . "','" . Constant::CODE_STATUS_FINISHED . "')) np " .
            "           INNER JOIN poker_tournaments t ON np.tournament_id = t.tournament_id " .
            "           INNER JOIN poker_group_payouts gp ON t.group_id = gp.group_id " .
            "           INNER JOIN poker_payouts p ON gp.payout_id = p.payout_id AND np.numPlayers BETWEEN p.payout_min_players AND p.payout_max_players) a " .
            "     INNER JOIN poker_structures s1 ON a.payout_id = s1.payout_id) s ON r.result_place_finished = s.structure_place " .
            "INNER JOIN (SELECT r1.tournament_id, COUNT(*) AS numPlayers " .
            "            FROM poker_results r1 " .
            "            WHERE r1.result_place_finished > 0 " .
            "            GROUP BY r1.tournament_id) np ON r.tournament_id = np.tournament_id " .
            "LEFT JOIN (SELECT r2.tournament_id, SUM(r2.result_rebuy_count) AS numRebuys " .
            "           FROM poker_results r2 " .
            "           WHERE r2.result_rebuy_count > 0 GROUP BY r2.tournament_id) nr ON r.tournament_id = nr.tournament_id " .
            "LEFT JOIN (SELECT r3.tournament_id, r3.player_id, CONCAT(p1.player_first_name, ' ', p1.player_last_name) AS knockedOutBy, p1.player_active_flag " .
            "           FROM poker_results r3 INNER JOIN poker_players p1 ON r3.player_id_ko = p1.player_id) ko ON r.tournament_id = ko.tournament_id AND r.player_id = ko.player_id " .
            "LEFT JOIN (SELECT tournament_id, COUNT(result_paid_addon_flag) AS numAddons " .
            "           FROM poker_results WHERE result_paid_addon_flag = '" . Constant::FLAG_YES . "' GROUP BY tournament_id) na ON r.tournament_id = na.tournament_id " .
            "WHERE r.tournament_id = :tournamentId2 " .
            "AND r.result_place_finished > 0 " .
            "ORDER BY t.tournament_date DESC, t.tournament_start_time DESC, r.result_place_finished";
        $statement = $this->getEntityManager()->getConnection()->prepare(sql: $sql);
        if (isset($tournamentId)) {
            $statement->bindValue(param: "tournamentId1", value: $tournamentId, type: PDO::PARAM_INT);
            $statement->bindValue(param: "tournamentId2", value: $tournamentId, type: PDO::PARAM_INT);
        }
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }

    public function getOrderedByPoints(?DateTime $startDate, ?DateTime $endDate, bool $indexed) {
        $sql =
            "SELECT CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, " .
            "       SUM((np.numPlayers - r.result_place_finished + 1) * IFNULL(st.special_type_multiplier, 1) + IF(r.result_place_finished BETWEEN 1 AND se.season_final_table_players, se.season_final_table_bonus_points, 0)) AS pts, " .
            "       SUM((np.numPlayers - r.result_place_finished + 1) * IFNULL(st.special_type_multiplier, 1) + IF(r.result_place_finished BETWEEN 1 AND se.season_final_table_players, se.season_final_table_bonus_points, 0)) / nt.numTourneys AS avg, " .
            "       nt.numTourneys AS tourneys, p.player_active_flag " .
            "FROM poker_players p " . "INNER JOIN poker_results r ON p.player_id = r.player_id " .
            "INNER JOIN poker_tournaments t on r.tournament_id = t.tournament_id ";
        if (isset($startDate) && isset($endDate)) {
            $sql .= "      AND t.tournament_date BETWEEN :startDate1 AND :endDate1 ";
        }
        $sql .=
            "INNER JOIN poker_seasons se ON t.tournament_date BETWEEN se.season_start_date AND se.season_end_date " .
            "LEFT JOIN poker_special_types st ON t.special_type_id = st.special_type_id" .
            " INNER JOIN (SELECT r1.player_id, COUNT(*) AS numTourneys FROM poker_results r1 INNER JOIN poker_tournaments t1 ON r1.tournament_id = t1.tournament_id AND r1.result_place_finished > 0";
        if (isset($startDate) && isset($endDate)) {
            $sql .= "      AND t1.tournament_date BETWEEN :startDate2 AND :endDate2 ";
        }
        $sql .=
            " LEFT JOIN poker_special_types st ON t1.special_type_id = st.special_type_id AND (st.special_type_description IS NULL OR st.special_type_description <> '" . Constant::DESCRIPTION_CHAMPIONSHIP . "')" .
            " GROUP BY r1.player_id) nt ON r.player_id = nt.player_id " .
            "INNER JOIN (SELECT tournament_id, COUNT(*) AS numPlayers " .
            "            FROM poker_results " .
            "            WHERE result_place_finished > 0 " .
            "            GROUP BY tournament_id) np ON r.tournament_id = np.tournament_id " .
            "WHERE st.special_type_description IS NULL OR st.special_type_description <> '" . Constant::DESCRIPTION_CHAMPIONSHIP . "'" .
            "GROUP BY r.player_id " .
            "ORDER BY pts DESC, p.player_last_name, p.player_first_name";
        $statement = $this->getEntityManager()->getConnection()->prepare(sql: $sql);
        if (isset($startDate) && isset($endDate)) {
            $startDateFormatted = DateTimeUtility::formatDatabaseDate(value: $startDate);
            $endDateFormatted = DateTimeUtility::formatDatabaseDate(value: $endDate);
            $statement->bindValue(param: "startDate1", value: $startDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "endDate1", value: $endDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "startDate2", value: $startDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "endDate2", value: $endDateFormatted, type: PDO::PARAM_STR);
        }
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }

    public function getOrderedByEarnings(?DateTime $startDate, ?DateTime $endDate, bool $indexed) {
        $sql =
            "SELECT name, SUM(totalEarnings) AS earnings, SUM(totalEarnings) / numTourneys AS avg, MAX(maxEarnings) AS max, numTourneys AS tourneys, player_active_flag " .
            "FROM (SELECT player_id, name, SUM(earnings) AS totalEarnings, MAX(earnings) AS maxEarnings, numTourneys, player_active_flag " .
            "      FROM (SELECT p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, " .
            "                   ((np.numPlayers * (t.tournament_buyin_amount - (t.tournament_buyin_amount * t.tournament_rake))) + " .
            "                    (IFNULL(nr.numRebuys, 0) * (t.tournament_rebuy_amount - (t.tournament_rebuy_amount * t.tournament_rake))) + " .
            "                    (IFNULL(na.numAddons, 0) * (t.tournament_addon_amount - (t.tournament_addon_amount * t.tournament_rake)))) * IFNULL(s.structure_percentage, 0) AS earnings, nt.numTourneys, p.player_active_flag " .
            "            FROM poker_results r INNER JOIN poker_players p ON r.player_id = p.player_id " .
            "            INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id ";
        if (isset($startDate) && isset($endDate)) {
            $sql .= "            AND t.tournament_date BETWEEN :startDate1 AND :endDate1 ";
        }
        $sql .=
            "            INNER JOIN (SELECT r1.player_id, COUNT(*) AS numTourneys " .
            "                        FROM poker_results r1 INNER JOIN poker_tournaments t1 ON r1.tournament_id = t1.tournament_id AND r1.result_place_finished > 0 ";
        if (isset($startDate) && isset($endDate)) {
            $sql .= "                       AND t1.tournament_date BETWEEN :startDate2 AND :endDate2 ";
        }
        $sql .=
            "                        GROUP BY r1.player_id) nt ON r.player_id = nt.player_id " .
            "            INNER JOIN (SELECT r2.tournament_id, COUNT(*) AS numPlayers " .
            "                        FROM poker_results r2 " .
            "                        WHERE r2.result_place_finished > 0 " .
            "                        GROUP BY r2.tournament_id) np ON r.tournament_id = np.tournament_id " .
            "            LEFT JOIN (SELECT r3.tournament_id, SUM(r3.result_rebuy_count) AS numRebuys " .
            "                       FROM poker_results r3 " .
            "                       WHERE r3.result_place_finished > 0 " .
            "                       AND r3.result_rebuy_count > 0 " .
            "                       GROUP BY r3.tournament_id) nr ON r.tournament_id = nr.tournament_id " .
            "            LEFT JOIN (SELECT tournament_id, COUNT(result_paid_addon_flag) AS numaddons " .
            "                       FROM poker_results " .
            "                       WHERE result_paid_addon_flag = '" . Constant::FLAG_YES . "' " .
            "                       GROUP BY tournament_id) na ON r.tournament_id = na.tournament_id " .
            "            LEFT JOIN (SELECT a.tournament_id, s1.payout_id, s1.structure_place, s1.structure_percentage " .
            "                       FROM (SELECT np.tournament_id, p.payout_id " .
            "                             FROM (SELECT r.tournament_id, COUNT(*) AS numPlayers " .
            "                                   FROM poker_results r " .
            "                                   WHERE r.result_place_finished > 0 " .
            "                                   AND r.status_code IN ('" . Constant::CODE_STATUS_REGISTERED . "','" . Constant::CODE_STATUS_FINISHED . "') " .
            "                                   GROUP BY r.tournament_id) np " .
            "                             INNER JOIN poker_tournaments t on np.tournament_id = t.tournament_id " .
            "                             INNER JOIN poker_group_payouts gp ON t.group_id = gp.group_id " .
            "                             INNER JOIN poker_payouts p ON gp.payout_id = p.payout_id AND np.numPlayers BETWEEN p.payout_min_players AND p.payout_max_players) a " .
            "                       INNER JOIN poker_structures s1 ON a.payout_id = s1.payout_id) s ON r.tournament_id = s.tournament_id AND r.result_place_finished = s.structure_place " .
            "                       WHERE r.result_place_finished > 0) y " .
            "            GROUP BY player_id " .
            "            UNION " .
            "            SELECT xx.player_id, xx.name, SUM(xx.earnings) AS totalEarnings, MAX(xx.earnings) AS maxEarnings, 0, xx.player_active_flag " .
            "            FROM (SELECT YEAR(t.tournament_date) AS yr, p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, p.player_active_flag, " .
            "                         (SELECT SUM(total) - IF(Yr = 2008, 150, IF(Yr = 2007, -291, IF(Yr = 2006, -824, 0))) AS 'total pool' " .
            "                          FROM (SELECT YEAR(t2.tournament_date) AS Yr, t2.tournament_id AS id, IF(b.play IS NULL, 0, CONCAT(b.play, '+', IFNULL(nr.numRebuys, 0), 'r', '+', IFNULL(na.numAddons, 0), 'a')) AS play, " .
            "                                       ((t2.tournament_buyin_amount * t2.tournament_rake) * play) + ((t2.tournament_rebuy_amount * t2.tournament_rake) * IFNULL(nr.numRebuys, 0)) + ((t2.tournament_addon_amount * t2.tournament_rake) * IFNULL(na.numaddons, 0)) AS total " .
            "                                FROM poker_tournaments t2 " .
            "                                LEFT JOIN (SELECT tournament_id, COUNT(*) AS play " .
            "                                           FROM poker_results " .
            "                                           WHERE result_paid_buyin_flag = '" . Constant::FLAG_YES . "' " .
            "                                           AND result_place_finished > 0 " .
            "                                           GROUP BY tournament_id) b ON t2.tournament_id = b.tournament_id";
        if (isset($startDate) && isset($endDate)) {
            $sql .= "                                AND t2.tournament_date BETWEEN :startDate3 AND :endDate3 ";
        }
        $sql .=
            "                               LEFT JOIN (SELECT r.tournament_id, SUM(r.result_rebuy_count) AS numRebuys " .
            "                                          FROM poker_results r " .
            "                                          WHERE r.result_paid_rebuy_flag = '" . Constant::FLAG_YES . "' " .
            "                                          AND r.result_rebuy_count > 0 " .
            "                                          GROUP BY r.tournament_id) nr ON t2.tournament_id = nr.tournament_id " .
            "                               LEFT JOIN (SELECT r.tournament_id, COUNT(*) AS numaddons " .
            "                                          FROM poker_results r " .
            "                                          WHERE r.result_paid_addon_flag = '" . Constant::FLAG_YES . "' " .
            "                                          GROUP BY r.tournament_id) na ON t2.tournament_id = na.tournament_id) zz " .
            "                         WHERE zz.yr = YEAR(t.tournament_date) " .
            "                         GROUP BY zz.yr) * IF(s.structure_percentage IS NULl, 0, s.structure_percentage) AS earnings " .
            "                 FROM poker_results r " .
            "                 INNER JOIN poker_players p ON r.player_id = p.player_id " .
            "                 INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id";
        if (isset($startDate) && isset($endDate)) {
            $sql .= "                  AND t.tournament_date BETWEEN :startDate4 AND :endDate4 ";
        }
        $sql .=
            "                 LEFT JOIN poker_special_types st ON t.special_type_id = st.special_type_id" .
            "                 LEFT JOIN (SELECT a.tournament_id, s1.payout_id, s1.structure_place, s1.structure_percentage " .
            "                            FROM (SELECT np.tournament_id, p.payout_id " .
            "                                  FROM (SELECT r.tournament_id, COUNT(*) AS numPlayers " .
            "                                        FROM poker_results r " .
            "                                        WHERE r.result_place_finished > 0 " .
            "                                        AND r.status_code IN ('" . Constant::CODE_STATUS_REGISTERED . "','" . Constant::CODE_STATUS_FINISHED . "') " .
            "                                        GROUP BY r.tournament_id) np " .
            "                                  INNER JOIN poker_tournaments t on np.tournament_id = t.tournament_id";
        if (isset($startDate) && isset($endDate)) {
            $sql .= "                                   AND t.tournament_date BETWEEN :startDate5 AND :endDate5 ";
        }
        $sql .=
            "                                  INNER JOIN poker_group_payouts gp ON t.group_id = gp.group_id " .
            "                                  INNER JOIN poker_payouts p ON gp.payout_id = p.payout_id AND np.numPlayers BETWEEN p.payout_min_players AND p.payout_max_players) a " .
            "                            INNER JOIN poker_structures s1 ON a.payout_id = s1.payout_id) s ON r.tournament_id = s.tournament_id AND r.result_place_finished = s.structure_place " .
            "                 WHERE r.result_place_finished > 0 " .
            "                 AND st.special_type_description = '" . Constant::DESCRIPTION_CHAMPIONSHIP . "'" .
            "                 GROUP BY player_id, yr) xx " .
            "            GROUP BY xx.player_id, xx.name) cc " .
            "GROUP BY player_id, name " .
            "ORDER BY earnings DESC";
        $statement = $this->getEntityManager()->getConnection()->prepare(sql: $sql);
        if (isset($startDate) && isset($endDate)) {
            $startDateFormatted = DateTimeUtility::formatDatabaseDate(value: $startDate);
            $endDateFormatted = DateTimeUtility::formatDatabaseDate(value: $endDate);
            $statement->bindValue(param: "startDate1", value: $startDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "endDate1", value: $endDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "startDate2", value: $startDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "endDate2", value: $endDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "startDate3", value: $startDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "endDate3", value: $endDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "startDate4", value: $startDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "endDate4", value: $endDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "startDate5", value: $startDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "endDate5", value: $endDateFormatted, type: PDO::PARAM_STR);
        }
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }

    public function getEarnings(?int $playerId, ?DateTime $startDate, ?DateTime $endDate, ?int $year, bool $championship, bool $season, bool $totalAndAverage, bool $rank, ?array $orderBy, ?int $limitCount, bool $indexed) {
        $sql = "";
        if (!$championship) {
            $sql .=
                "SELECT p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, IFNULL(earns, 0) AS earns, IFNULL(earns / trnys, 0) AS avg, IFNULL(trnys, 0) AS trnys, p.player_active_flag " .
                "FROM poker_players p " .
                "LEFT JOIN (SELECT player_id, SUM(totalEarnings) AS earns, numTourneys AS trnys " .
                "           FROM (SELECT player_id, player_first_name, player_last_name, SUM(earnings) AS totalEarnings, MAX(earnings) AS maxEarnings, numTourneys " .
                "                 FROM (SELECT p.player_id, p.player_first_name, p.player_last_name, " .
                "                              ((np.numPlayers * (t.tournament_buyin_amount - (t.tournament_buyin_amount * t.tournament_rake))) + " .
                "                               (IFNULL(nr.numRebuys, 0) * (t.tournament_rebuy_amount - (t.tournament_rebuy_amount * t.tournament_rake))) + " .
                "                               (IFNULL(na.numAddons, 0) * (t.tournament_addon_Amount - (t.tournament_addon_amount * t.tournament_rake)))) * IFNULL(s.structure_percentage, 0) AS Earnings, " .
                "                              nt.NumTourneys " .
                "            FROM poker_players p " .
                "            INNER JOIN poker_results r ON p.player_id = r.player_id " .
                "            INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id ";
                if (!(isset($playerId) && !$season) && isset($startDate) && isset($endDate)) {
                    $sql .= "            AND t.tournament_date BETWEEN :startDate1 AND :endDate1 ";
                }
                $sql .=
                "            INNER JOIN (SELECT r1.player_id, COUNT(*) AS NumTourneys " .
                "                        FROM poker_results r1 " .
                "                        INNER JOIN poker_tournaments t1 ON r1.tournament_id = t1.tournament_id AND r1.result_place_finished > 0 ";
                if (!(isset($playerId) && !$season) && isset($startDate) && isset($endDate)) {
                    $sql .= "                        AND t1.tournament_date BETWEEN :startDate2 AND :endDate2 ";
                }
                $sql .=
                    "                        GROUP BY r1.player_id) nt ON r.player_id = nt.player_id " .
                    "            INNER JOIN (SELECT r2.tournament_id, COUNT(*) AS numPlayers " .
                    "                        FROM poker_results r2 " .
                    "                        WHERE r2.result_place_finished > 0 " .
                    "                        GROUP BY r2.tournament_id) np ON r.tournament_id = np.tournament_id " .
                    "            LEFT JOIN (SELECT r3.tournament_id, SUM(r3.result_rebuy_count) AS numRebuys " .
                    "                       FROM poker_results r3 " .
                    "                       WHERE r3.result_place_finished > 0 " .
                    "                       AND r3.result_rebuy_count > 0 " .
                    "                       GROUP BY r3.tournament_id) nr ON r.tournament_id = nr.tournament_id " .
                    "            LEFT JOIN (SELECT tournament_id, COUNT(result_paid_addon_flag) AS numAddons " .
                    "                       FROM poker_results " .
                    "                       WHERE result_paid_addon_flag = '" . Constant::FLAG_YES . "' " .
                    "                       GROUP BY tournament_id) na ON r.tournament_id = na.tournament_id " .
                    "            LEFT JOIN (SELECT a.tournament_id, s1.payout_id, s1.structure_place, s1.structure_percentage " .
                    "                       FROM (SELECT np.tournament_id, p.payout_id " .
                    "                             FROM (SELECT r.tournament_id, COUNT(*) AS numPlayers " .
                    "                                   FROM poker_results r " .
                    "                                   WHERE r.status_code IN ('" . Constant::CODE_STATUS_REGISTERED . "', '" . Constant::CODE_STATUS_FINISHED . "') " .
                    "                                   GROUP BY r.tournament_id) np " .
                    "                             INNER JOIN poker_tournaments t on np.tournament_id = t.tournament_id " .
                    "                             INNER JOIN poker_group_payouts gp ON t.group_id = gp.group_id " .
                    "                             INNER JOIN poker_payouts p ON gp.payout_id = p.payout_id AND np.numPlayers BETWEEN p.payout_min_players AND p.payout_max_players) a " .
                    "                       INNER JOIN poker_structures s1 ON a.payout_id = s1.payout_id) s ON r.tournament_id = s.tournament_id AND r.result_place_finished = s.structure_place WHERE r.result_place_finished > 0) y " .
                    "            GROUP BY player_id ";
                if (!($season && !isset($playerId))) {
                    $sql .= "            UNION ";
                }
        } else {
            $sql .= "             SELECT player_id, CONCAT(player_first_name, ' ', player_last_name) AS name, IFNULL(totalEarnings, 0) AS earns";
            if ($championship) {
                $sql .= ", IFNULL(totalEarnings / trnys, 0) AS avg, IFNULL(trnys, 0) AS trnys ";
            }
            $sql .= "             FROM (";
        }
        if (!($season && !isset($playerId))) {
            $sql .= "            SELECT xx.player_id, xx.player_last_name, xx.player_first_name, SUM(xx.earnings) AS totalEarnings, MAX(xx.earnings) AS maxEarnings, 0";
            if ($championship) {
                $sql .= ", numTourneys AS trnys ";
            }
            $sql .= "            FROM (SELECT Yr, p.player_id, p.player_first_name, p.player_last_name, ";
            if ($championship) {
                $sql .= " numTourneys, ";
            }
            $sql .=
                "                        qq.total * IFNULL(s.structure_percentage, 0) AS Earnings " .
                "                 FROM poker_players p " .
                "                 INNER JOIN poker_results r ON p.player_id = r.player_id " .
                "                 INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id";
            if (!(isset($playerId) && !$season) && !$championship && isset($startDate) && isset($endDate)) {
                $sql .= "        AND t.tournament_date BETWEEN :startDate3 AND :endDate3 ";
            }
            if ($championship && isset($year)) {
                $sql .= "                               AND YEAR(t.tournament_date) IN (:tournamentDate1) ";
            }
            $sql .=
                "                  INNER JOIN (SELECT Yr, SUM(total) - IF(Yr = 2008, 150, " . // adjust to match Dave W stats
                "                                                       IF(Yr = 2007, -291, " . // adjust to match Dave W stats
                "                                                        IF(Yr = 2006, -824, 0))) " . // adjust to match Dave W stats
                "                                         AS total " .
                "                         FROM (SELECT YEAR(t2.tournament_date) AS Yr, t2.tournament_id AS Id, IFNULL(b.Play, 0) AS Play, " .
                "                                      ((t2.tournament_buyin_amount * t2.tournament_rake) * Play) + " .
                "                                      ((t2.tournament_rebuy_amount * t2.tournament_rake) * IFNULL(nr.numRebuys, 0)) + " .
                "                                      ((t2.tournament_addon_amount * t2.tournament_rake) * IFNULL(na.numAddons, 0)) AS Total " .
                "                               FROM poker_tournaments t2 LEFT JOIN (SELECT tournament_id, COUNT(*) AS Play " .
                "                                                                   FROM poker_results " .
                "                                                                   WHERE result_paid_buyin_flag = '" . Constant::FLAG_YES . "' " .
                "                                                                   AND result_place_finished > 0 " .
                "                                                                   GROUP BY tournament_id) b ON t2.tournament_id = b.tournament_id";
            if (!(isset($playerId) && !$season) && !$championship && isset($startDate) && isset($endDate)) {
                $sql .= "                               AND t2.tournament_date BETWEEN :startDate4 AND :endDate4 ";
            }
            if ($championship && isset($year)) {
                $sql .= "                               AND YEAR(t2.tournament_date) IN (:tournamentDate2) ";
            }
            $sql .=
            "                              LEFT JOIN (SELECT r.tournament_id, SUM(r.result_rebuy_count) AS numRebuys " .
            "                                         FROM poker_results r " .
            "                                         WHERE r.result_paid_rebuy_flag = '" . Constant::FLAG_YES . "' " .
            "                                         AND r.result_rebuy_count > 0 " .
            "                                         GROUP BY r.tournament_id) nr ON t2.tournament_id = nr.tournament_id " .
            "                              LEFT JOIN (SELECT r.tournament_id, COUNT(*) AS numAddons " .
            "                                         FROM poker_results r " .
            "                                         WHERE r.result_paid_addon_flag = '" . Constant::FLAG_YES . "' " .
            "                                         GROUP BY r.tournament_id) na ON t2.tournament_id = na.tournament_id) zz " .
            "                        GROUP BY zz.yr) qq";
            $sql .=
                " ON qq.yr = YEAR(t.tournament_date) " .
                "                  LEFT JOIN poker_special_types st ON t.special_type_id = st.special_type_id";
            if ($championship) {
                $sql .= "                  INNER JOIN (SELECT r1.player_id, COUNT(*) AS NumTourneys FROM poker_results r1 INNER JOIN poker_tournaments t1 ON r1.tournament_id = t1.tournament_id AND r1.result_place_finished > 0 INNER JOIN poker_special_types st1 ON t1.special_type_id = st1.special_type_id AND st1.special_type_description = '" . Constant::DESCRIPTION_CHAMPIONSHIP . "' GROUP BY r1.player_id) nt ON r.player_id = nt.player_id ";
            }
            $sql .=
                "                  LEFT JOIN (SELECT a.tournament_id, s1.payout_id, s1.structure_place, s1.structure_percentage " .
                "                             FROM (SELECT np.tournament_id, p.payout_id " .
                "                                   FROM (SELECT r.tournament_id, COUNT(*) AS numPlayers " .
                "                                         FROM poker_results r " .
                "                                         WHERE r.status_code IN ('" . Constant::CODE_STATUS_REGISTERED . "','" . Constant::CODE_STATUS_FINISHED . "') " .
                "                                         GROUP BY r.tournament_id) np " .
                "                                   INNER JOIN poker_tournaments t on np.tournament_id = t.tournament_id";
            if (!(isset($playerId) && !$season) && isset($startDate) && isset($endDate)) {
                $sql .= "                                    AND t.tournament_date BETWEEN :startDate5 AND :endDate5 ";
            }
            if ($championship && isset($year)) {
                $sql .= "                               AND YEAR(t.tournament_date) IN (:tournamentDate3) ";
            }
            $sql .=
                "                                   INNER JOIN poker_group_payouts gp ON t.group_id = gp.group_id " .
                "                                   INNER JOIN poker_payouts p ON gp.payout_id = p.payout_id AND np.numPlayers BETWEEN p.payout_min_players AND p.payout_max_players) a " .
                "                             INNER JOIN poker_structures s1 ON a.payout_id = s1.payout_id) s ON r.tournament_id = s.tournament_id AND r.result_place_finished = s.structure_place " .
                "                  WHERE r.result_place_finished > 0 " .
                "                  AND st.special_type_description = '" . Constant::DESCRIPTION_CHAMPIONSHIP . "'" .
                "                  GROUP BY player_id, yr) xx ";
        }
        if ($championship) {
            $sql .=
                "        GROUP BY xx.player_id, xx.player_last_name, xx.player_first_name " .
                "        ORDER BY totalearnings desc, xx.player_last_name, xx.player_first_name) a";
        } else {
            if ($season && !$totalAndAverage) {
                $sql .= ") cc ";
            } else {
                $sql .= "      GROUP BY xx.player_id, xx.player_last_name, xx.player_first_name) cc ";
            }
            $sql .= "GROUP BY player_id) z ON p.player_id = z.player_id " .
                    " WHERE p.player_active_flag = " . Constant::FLAG_YES_DATABASE;
            if ($totalAndAverage) {
                $whereClause = " AND p.player_id = " . $playerId;
                $sql .= " AND p.player_id = " . $playerId;
            }
            if (!$totalAndAverage) {
                $sql .= " ORDER BY ";
                if (1 == $orderBy[0]) {
                    $sql .= "earns";
                } else {
                    $sql .= "avg";
                }
                $sql .= " DESC, player_last_name, player_first_name";
            }
            if ($championship) {
                $sql .= ")";
            }
            if ($rank) {
                if (1 == $orderBy[0]) {
                    $orderByFieldName = "earns DESC, player_last_name, player_first_name";
                    $selectFieldName = "earns";
                } else {
                    $orderByFieldName = "avg DESC, player_last_name, player_first_name";
                    $selectFieldName = "avg";
                }
                $selectFieldNames = "player_id, name, earns, avg, trnys";
                $sql = $this->modifyQueryAddRank(query: $sql, whereClause: $whereClause, selectFieldName: $selectFieldName, selectFieldNames: $selectFieldNames, orderByFieldName: $orderByFieldName);
            }
        }
        if (isset($limitCount)) {
            $sql .= " LIMIT :limitCount";
        }
        $statement = $this->getEntityManager()->getConnection()->prepare(sql: $sql);
        if (!$championship && !(isset($playerId) && !$season) && isset($startDate) && isset($endDate)) {
            $startDateFormatted = DateTimeUtility::formatDatabaseDate(value: $startDate);
            $endDateFormatted = DateTimeUtility::formatDatabaseDate(value: $endDate);
            $statement->bindValue(param: "startDate1", value: $startDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "endDate1", value: $endDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "startDate2", value: $startDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "endDate2", value: $endDateFormatted, type: PDO::PARAM_STR);
            if (!($season && !isset($playerId))) {
                $statement->bindValue(param: "startDate3", value: $startDateFormatted, type: PDO::PARAM_STR);
                $statement->bindValue(param: "endDate3", value: $endDateFormatted, type: PDO::PARAM_STR);
                $statement->bindValue(param: "startDate4", value: $startDateFormatted, type: PDO::PARAM_STR);
                $statement->bindValue(param: "endDate4", value: $endDateFormatted, type: PDO::PARAM_STR);
                $statement->bindValue(param: "startDate5", value: $startDateFormatted, type: PDO::PARAM_STR);
                $statement->bindValue(param: "endDate5", value: $endDateFormatted, type: PDO::PARAM_STR);
            }
        } else if ($championship && isset($year)) {
            $statement->bindValue(param: "tournamentDate1", value: $year, type: PDO::PARAM_INT);
            $statement->bindValue(param: "tournamentDate2", value: $year, type: PDO::PARAM_INT);
            $statement->bindValue(param: "tournamentDate3", value: $year, type: PDO::PARAM_INT);
        }
        if (isset($limitCount)) {
            $statement->bindValue(param: "limitCount", value: $limitCount, type: PDO::PARAM_INT);
        }
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }
    public function getOrderedByKos(?DateTime $startDate, ?DateTime $endDate, bool $indexed) {
        $sql =
            "SELECT k.player_id, name, k.knockouts AS kos, k.knockouts / nt.numTourneys AS avgKo, b.bestKnockout AS bestKo, nt.numTourneys AS tourneys, k.player_active_flag " .
            "FROM (SELECT p3.player_id, CONCAT(p3.player_first_name, ' ', p3.player_last_name) AS name, p.player_active_flag, COUNT(*) AS knockouts " .
            "      FROM poker_tournaments t " .
            "      INNER JOIN poker_limit_types lt ON t.limit_type_id = lt.limit_type_id " .
            "      INNER JOIN poker_game_types gt ON t.game_type_id = gt.game_type_id " .
            "      INNER JOIN poker_locations l ON t.location_id = l.location_id " .
            "      INNER JOIN poker_players p ON l.player_id = p.player_id " .
            "      INNER JOIN poker_results r ON t.tournament_id = r.tournament_id " .
            "      INNER JOIN poker_players p2 ON r.player_id = p2.player_id " .
            "      INNER JOIN poker_status_codes sc ON r.status_code = sc.status_code " .
            "      INNER JOIN poker_players p3 ON r.player_id_ko = p3.player_id " .
            "      WHERE r.result_place_finished > 0 ";
        if (isset($startDate) && isset($endDate)) {
            $sql .= "      AND t.tournament_date BETWEEN :startDate1 AND :endDate1 ";
        }
        $sql .=
            "      GROUP BY r.player_id_ko) k " .
            "INNER JOIN (SELECT r.player_id, COUNT(*) AS numTourneys " .
            "            FROM poker_tournaments t " .
            "            INNER JOIN poker_limit_types lt ON t.limit_type_id = lt.limit_type_id " .
            "            INNER JOIN poker_game_types gt ON t.game_type_id = gt.game_type_id " .
            "            INNER JOIN poker_locations l ON t.location_id = l.location_id " .
            "            INNER JOIN poker_players p ON l.player_id = p.player_id " .
            "            INNER JOIN poker_results r ON t.tournament_id = r.tournament_id " .
            "            INNER JOIN poker_players p2 ON r.player_id = p2.player_id " .
            "            INNER JOIN poker_status_codes sc ON r.status_code = sc.status_code " .
            "            LEFT JOIN poker_players p3 ON r.player_id_ko = p3.player_id " .
            "            WHERE r.result_place_finished > 0 ";
        if (isset($startDate) && isset($endDate)) {
            $sql .= "            AND t.tournament_date BETWEEN :startDate2 AND :endDate2 ";
        }
        $sql .=
            "            GROUP BY r.player_id) nt ON k.player_id = nt.player_id " .
            "LEFT JOIN (SELECT player_id, MAX(knockouts) AS BestKnockout " .
            "           FROM (SELECT p3.player_id, t.tournament_id, r.player_id_ko, COUNT(*) AS knockouts " .
            "                 FROM poker_tournaments t INNER JOIN poker_limit_types lt ON t.limit_type_id = lt.limit_type_id " .
            "                 INNER JOIN poker_game_types gt ON t.game_type_id = gt.game_type_id " .
            "                 INNER JOIN poker_locations l ON t.location_id = l.location_id " .
            "                 INNER JOIN poker_players p ON l.player_id = p.player_id " .
            "                 INNER JOIN poker_results r ON t.tournament_id = r.tournament_id " .
            "                 INNER JOIN poker_players p2 ON r.player_id = p2.player_id " .
            "                 INNER JOIN poker_status_codes sc ON r.status_code = sc.status_code " .
            "                 INNER JOIN poker_players p3 ON r.player_id_ko = p3.player_id " .
            "                 WHERE r.result_place_finished > 0 " .
            "                 AND r.player_id_ko IS NOT NULL ";
        if (isset($startDate) && isset($endDate)) {
            $sql .= "                 AND t.tournament_date BETWEEN :startDate3 AND :endDate3 ";
        }
        $sql .=
            "                 GROUP BY t.tournament_id, r.player_id_ko) z " .
            "           GROUP BY z.player_id) b ON nt.player_id = b.player_id " .
            "WHERE b.player_id IN (SELECT DISTINCT player_id " .
            "               FROM poker_results " .
            "               WHERE status_code = '" . Constant::CODE_STATUS_FINISHED . "') " .
            "ORDER BY k.knockouts DESC, nt.NumTourneys";
        $statement = $this->getEntityManager()->getConnection()->prepare(sql: $sql);
        if (isset($startDate) && isset($endDate)) {
            $startDateFormatted = DateTimeUtility::formatDatabaseDate(value: $startDate);
            $endDateFormatted = DateTimeUtility::formatDatabaseDate(value: $endDate);
            $statement->bindValue(param: "startDate1", value: $startDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "endDate1", value: $endDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "startDate2", value: $startDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "endDate2", value: $endDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "startDate3", value: $startDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "endDate3", value: $endDateFormatted, type: PDO::PARAM_STR);
        }
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }

    public function getOrderedSummary(?DateTime $currentDate, ?DateTime $startDate, ?DateTime $endDate, bool $championship, bool $stats, bool $indexed) {
        $sql = "SELECT ";
        if ($stats) {
            $sql .= "player_id, ";
        }
        $sql .=
            "name, d.tourneys AS '#', " . (NULL !== $startDate ? "d.NumTourneysLeftSeason" : 0) . " AS 'left', " . (NULL !== $startDate ? "IF(d.tourneys >= d.ChampQualCount, 0, d.ChampQualCount - d.tourneys)" : 0) . " AS needed, " .
            "IFNULL(d.Points, 0) AS pts, d.Points / d.numTourneys AS AvgPoints, d.FTs AS 'count', d.pctFTs AS '%', d.avgPlace AS 'avg', d.high AS 'best', d.low AS 'worst', " .
            "-(IF(d.numTourneys IS NULL, 0, d.numTourneys * d.tournament_buyin_amount)) AS buyins, -(IFNULL(d.rebuys, 0)) AS rebuys, " .
            "-(IFNULL(d.addons, 0)) AS addons, -(IF(d.numTourneys IS NULL, 0, d.numTourneys * d.tournament_buyin_amount)) + -(IFNULL(d.rebuys, 0)) + -(IFNULL(d.addons, 0)) AS 'total', " .
            "d.earnings,  " .
            "d.earnings - IF(d.numTourneys IS NULL, 0, d.numTourneys * d.tournament_buyin_amount) - IFNULL(d.rebuys, 0) - IFNULL(d.addons, 0) AS 'net(+/-)', " .
            "d.earnings / d.numTourneys AS '$ / trny', " .
            "(d.earnings - IF(d.numTourneys IS NULL, 0, d.numTourneys * d.tournament_buyin_amount) - IFNULL(d.rebuys, 0) - IFNULL(d.addons, 0)) / d.numTourneys AS 'Net / Trny', " .
            "player_active_flag " .
            "FROM (SELECT a.player_id, a.name, a.player_active_flag, a.Tourneys, a.FTs, a.PctFTs, a.AvgPlace, a.Low, a.High, IFNULL(b.Earnings, 0) AS earnings, a.NumTourneys, " .
            "             e.result_place_finished, e.NumPlayers, e.Points, e.Rebuys, e.Addons, e.NumRebuys, e.tournament_buyin_amount";
        if (NULL !== $startDate) {
            $sql .= ", IF(g.NumTourneysLeftSeason > 0, g.NumTourneysLeftSeason - 1, g.NumTourneysLeftSeason) AS NumTourneysLeftSeason, h.ChampQualCount";
        }
        $sql .= "      FROM (SELECT p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, IFNULL(nt.NumTourneys, 0) AS Tourneys, IFNULL(nft.NumFinalTables, 0) AS FTs, " .
            "                   IF(nt.NumTourneys IS NULL, 0, IFNULL(nft.NumFinalTables, 0) / nt.NumTourneys) AS PctFTs, " .
            "                   IF(nt.NumTourneys IS NULL, 0, IFNULL(nt.TotalPlaces, 0) / nt.NumTourneys) AS AvgPlace, " .
            "                   IFNULL(nt.MaxPlace, 0) AS Low, IFNULL(nt.MinPlace, 0) AS High, nt.NumTourneys, p.player_active_flag " .
            "            FROM poker_players p " .
            "            LEFT JOIN (SELECT r1.player_id, COUNT(*) AS NumTourneys, SUM(r1.result_place_finished) AS TotalPlaces, MAX(r1.result_place_finished) AS MaxPlace, MIN(r1.result_place_finished) AS MinPlace " .
            "                       FROM poker_results r1 " .
            "                       INNER JOIN poker_tournaments t1 ON r1.tournament_id = t1.tournament_id";
        if (isset($startDate) && isset($endDate)) {
//         if (NULL !== $startDate) {
            $sql .= "                       AND t1.tournament_date BETWEEN :startDate1 AND :endDate1 ";
        }
        $sql .=
            "                       WHERE r1.result_place_finished > 0 " .
            "                       GROUP BY r1.player_id) nt ON p.player_id = nt.player_id " .
            "            LEFT JOIN (SELECT r2.player_id, COUNT(*) AS NumFinalTables " .
            "                       FROM poker_results r2 " .
            "                       INNER JOIN poker_tournaments t2 ON r2.tournament_id = t2.tournament_id";
        if (isset($startDate) && isset($endDate)) {
//         if (NULL !== $startDate) {
            $sql .= "                       AND t2.tournament_date BETWEEN :startDate2 AND :endDate2 ";
        }
        $sql .=
            "                       INNER JOIN poker_seasons se ON t2.tournament_date BETWEEN se.season_start_date AND se.season_end_date " .
            "                       WHERE r2.result_place_finished BETWEEN 1 AND se.season_final_table_players " .
            "                       GROUP BY r2.player_id) nft ON p.player_id = nft.player_id) a " .
            "            LEFT JOIN (SELECT player_id, name, SUM(TotalEarnings) AS Earnings, MAX(MaxEarnings) AS MaxEarnings, numTourneys " .
            "                       FROM (SELECT player_id, name, SUM(Earnings) AS TotalEarnings, MAX(Earnings) AS MaxEarnings, NumTourneys " .
            "                             FROM (SELECT p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, " .
            "                                         ((np.NumPlayers * (t.tournament_buyin_amount - (t.tournament_buyin_amount * t.tournament_rake))) + " .
            "                                          (IFNULL(nr.NumRebuys, 0) * (t.tournament_rebuy_amount - (t.tournament_rebuy_amount * t.tournament_rake))) + " .
            "                                          (IFNULL(na.NumAddons, 0) * (t.tournament_addon_amount - (t.tournament_addon_amount * t.tournament_rake)))) * IFNULL(s.structure_percentage, 0) AS Earnings, nt.NumTourneys " .
            "                                   FROM poker_results r INNER JOIN poker_players p ON r.player_id = p.player_id " .
            "                                   INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id";
        if (isset($startDate) && isset($endDate)) {
//         if (NULL !== $startDate) {
            $sql .= "                                   AND t.tournament_date BETWEEN :startDate3 AND :endDate3 ";
        }
        $sql .=
            "                                   INNER JOIN (SELECT r1.player_id, COUNT(*) AS NumTourneys " .
            "                                               FROM poker_results r1 " .
            "                                               INNER JOIN poker_tournaments t1 ON r1.tournament_id = t1.tournament_id AND r1.result_place_finished > 0";
        if (isset($startDate) && isset($endDate)) {
//         if (NULL !== $startDate) {
            $sql .= "                                               AND t1.tournament_date BETWEEN :startDate4 AND :endDate4 ";
        }
        if ($championship) {
            $queryAndBindParams = $this->buildChampionship(array($startDate, $endDate));
        }
        $sql .=
            "                                               GROUP BY r1.player_id) nt ON r.player_id = nt.player_id " .
            "                                   INNER JOIN (SELECT r2.tournament_id, COUNT(*) AS NumPlayers " .
            "                                               FROM poker_results r2 " .
            "                                               WHERE r2.result_place_finished > 0 " .
            "                                               GROUP BY r2.tournament_id) np ON r.tournament_id = np.tournament_id " .
            "                                   LEFT JOIN (SELECT r3.tournament_id, SUM(r3.result_rebuy_count) AS NumRebuys " .
            "                                              FROM poker_results r3 " .
            "                                              WHERE r3.result_place_finished > 0 " .
            "                                              AND r3.result_rebuy_count > 0 GROUP BY r3.tournament_id) nr ON r.tournament_id = nr.tournament_id " .
            "                                   LEFT JOIN (SELECT tournament_id, COUNT(result_paid_addon_flag) AS NumAddons " .
            "                                              FROM poker_results " .
            "                                              WHERE result_paid_addon_flag = '" . Constant::FLAG_YES . "' " .
            "                                              GROUP BY tournament_id) na ON r.tournament_id = na.tournament_id " .
            "                                   LEFT JOIN (SELECT a.tournament_id, s1.payout_id, s1.structure_place, s1.structure_percentage " .
            "                                              FROM (SELECT np.tournament_id, p.payout_id " .
            "                                                    FROM (SELECT r.tournament_id, COUNT(*) AS numPlayers " .
            "                                                          FROM poker_results r " .
            "                                                          WHERE r.result_place_finished > 0 " .
            "                                                          AND r.status_code IN ('" . Constant::CODE_STATUS_REGISTERED . "','" . Constant::CODE_STATUS_FINISHED . "') " .
            "                                                          GROUP BY r.tournament_id) np INNER JOIN poker_tournaments t on np.tournament_id = t.tournament_id " .
            "                                                    INNER JOIN poker_group_payouts gp ON t.group_id = gp.group_id " .
            "                                                    INNER JOIN poker_payouts p ON gp.payout_id = p.payout_id AND np.numPlayers BETWEEN p.payout_min_players AND p.payout_max_players) a " .
            "                                              INNER JOIN poker_structures s1 ON a.payout_id = s1.payout_id) s ON r.tournament_id = s.tournament_id AND r.result_place_finished = s.structure_place " .
            "                                   WHERE r.result_place_finished > 0) y " .
            "                             GROUP BY player_id ";
        if ($championship) {
            $sql .=
                "                             UNION " .
                "                             SELECT xx.player_id, xx.name, SUM(xx.earnings) AS TotalEarnings, MAX(xx.earnings) AS MaxEarnings, 0 " .
                "                             FROM (" . $queryAndBindParams[0] .
                "                                   GROUP BY player_id, yr) xx " .
                "                             GROUP BY xx.player_id, xx.name) cc ";
        } else {
            $sql .= ") cc ";
        }
        $sql .= "                       GROUP BY player_id, name) b ON a.player_id = b.player_id " .
            "LEFT JOIN (SELECT c.player_id, c.result_place_finished, c.NumPlayers, SUM((c.numPlayers - c.result_place_finished + 1) * IFNULL(c.special_type_multiplier, 1) + IF(c.result_place_finished BETWEEN 1 AND c.season_final_table_players, c.season_final_table_bonus_points, 0)) AS Points, " .
            "                                                     SUM(IFNULL(c.NumRebuys, 0) * c.tournament_rebuy_amount) AS Rebuys, " .
            "                                                     SUM(IFNULL(c.NumAddons, 0) * c.tournament_addon_amount) AS Addons, " .
            "                                                     c.NumRebuys, c.tournament_buyin_amount " .
            "           FROM (SELECT a.tournament_id, a.tournament_description, a.player_id, a.result_place_finished, a.NumPlayers, a.NumRebuys, a.tournament_buyin_amount, a.tournament_rebuy_amount, a.tournament_addon_amount, a.NumAddons, a.special_type_description, a.special_type_multiplier, a.season_final_table_players, a.season_final_table_bonus_points " .
            "                 FROM (SELECT r.tournament_id, t.tournament_description, r.player_id, r.result_place_finished, np.NumPlayers, nr.NumRebuys, t.tournament_buyin_amount, t.tournament_rebuy_amount, t.tournament_addon_amount, na.NumAddons, st.special_type_description, st.special_type_multiplier, se.season_final_table_players, se.season_Final_table_bonus_points " .
            "                       FROM poker_results r INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id";
        if (isset($startDate) && isset($endDate)) {
//         if (NULL !== $startDate) {
            $sql .= "                       AND t.tournament_date BETWEEN :startDate5 AND :endDate5 ";
        }
        $sql .=
            "                       AND r.result_place_finished > 0 " .
            "                       INNER JOIN poker_seasons se ON t.tournament_date BETWEEN se.season_start_date AND se.season_end_date " .
            "                       LEFT JOIN poker_special_types st ON t.special_type_id = st.special_type_id" .
            "                       INNER JOIN (SELECT r3.tournament_id, COUNT(*) AS NumPlayers " .
            "                                   FROM poker_results r3 INNER JOIN poker_tournaments t3 ON r3.tournament_id = t3.tournament_id";
        if (isset($startDate) && isset($endDate)) {
//         if (NULL !== $startDate) {
            $sql .= "                                   AND t3.tournament_date BETWEEN :startDate6 AND :endDate6 ";
        }
        $sql .=
            "                                   WHERE r3.result_place_finished > 0 " .
            "                                   GROUP BY r3.tournament_id) np ON r.tournament_id = np.tournament_id " .
            "                       LEFT JOIN (SELECT r4.tournament_id, r4.player_id, SUM(r4.result_rebuy_count) AS NumRebuys " .
            "                                  FROM poker_results r4 " .
            "                                  INNER JOIN poker_tournaments t4 ON r4.tournament_id = t4.tournament_id";
        if (isset($startDate) && isset($endDate)) {
//         if (NULL !== $startDate) {
            $sql .= "                                  AND t4.tournament_date BETWEEN :startDate7 AND :endDate7 ";
        }
        $sql .=
            "                                  WHERE r4.result_place_finished > 0 " .
            "                                  AND r4.result_rebuy_count > 0 " .
            "                                  GROUP BY r4.tournament_id, r4.player_id) nr ON r.tournament_id = nr.tournament_id AND r.player_id = nr.player_id " .
            "                       LEFT JOIN (SELECT tournament_id, player_id, COUNT(result_paid_addon_flag) AS NumAddons " .
            "                                  FROM poker_results r7 WHERE result_paid_addon_flag = '" . Constant::FLAG_YES . "' GROUP BY tournament_id, player_id) na ON r.tournament_id = na.tournament_id AND r.player_id = na.player_id) a " .
            "                 ) c " .
            "           GROUP BY c.player_id) e ON b.player_id = e.player_id ";
        if (NULL !== $startDate) {
            $sql .=
                "LEFT JOIN (SELECT p.player_id, t.NumTourneysLeftSeason FROM poker_players p CROSS JOIN (SELECT COUNT(*) AS NumTourneysLeftSeason FROM poker_tournaments t1 WHERE t1.tournament_date BETWEEN :startDate8 AND :endDate1) t) g ON b.player_id = g.player_id " .
                "LEFT JOIN (SELECT p.player_id, t.ChampQualCount FROM poker_players p CROSS JOIN (SELECT s.season_championship_qualification_count AS ChampQualCount FROM poker_seasons s WHERE s.season_start_date = :startDate1) t) h ON b.player_id = h.player_id ";
        }
        $sql .= "WHERE b.player_id IN (SELECT DISTINCT player_id FROM poker_results WHERE status_code = '" . Constant::CODE_STATUS_FINISHED . "')) d ";
        if (!$stats) {
            $sql .= "ORDER BY ROUND(d.earnings, 0) DESC";
        }
        $statement = $this->getEntityManager()->getConnection()->prepare(sql: $sql);
        if (isset($startDate) && isset($endDate)) {
//         if (NULL !== $startDate) {
            $startDateFormatted = DateTimeUtility::formatDatabaseDate(value: $startDate);
            $endDateFormatted = DateTimeUtility::formatDatabaseDate(value: $endDate);
            $statement->bindValue(param: "startDate1", value: $startDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "endDate1", value: $endDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "startDate2", value: $startDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "endDate2", value: $endDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "startDate3", value: $startDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "endDate3", value: $endDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "startDate4", value: $startDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "endDate4", value: $endDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "startDate5", value: $startDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "endDate5", value: $endDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "startDate6", value: $startDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "endDate6", value: $endDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "startDate7", value: $startDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "endDate7", value: $endDateFormatted, type: PDO::PARAM_STR);
        }
//         if (isset($currentDate)) {
        if (NULL !== $startDate) {
            $currentDateFormatted = DateTimeUtility::formatDatabaseDate(value: $currentDate);
            $statement->bindValue(param: "startDate8", value: $currentDateFormatted, type: PDO::PARAM_STR);
        }
        if ($championship) {
            if (isset($queryAndBindParams[1])) {
                foreach ($queryAndBindParams[1] as $key => $value) {
                    $statement->bindValue(param: $key, value: DateTimeUtility::formatDatabaseDate(value: $value), type: PDO::PARAM_STR);
                }
            }
        }
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }

    public function updateRegistrationOrder(int $tournamentId, int $resultRegistrationOrder): int {
        return $this->getEntityManager()->getConnection()->executeStatement(sql: "UPDATE poker_results SET result_registration_order = result_registration_order - 1 WHERE tournament_id = ? AND result_registration_order > ?", params: [$tournamentId, $resultRegistrationOrder], types: [PDO::PARAM_INT, PDO::PARAM_INT]);
    }

    public function updateFinish(?int $rebuyCount, ?string $rebuyPaidFlag, ?string $addonPaidFlag, ?string $addonFlag, ?string $statusCode, ?int $place, ?int $playerIdKo, int $tournamentId, ?int $playerId): int {
        $sql =
            "UPDATE poker_results " .
            "SET result_paid_buyin_flag = '" . Constant::FLAG_YES . "'" .
            (isset($rebuyCount) ? ", result_rebuy_count = ?" : "") .
            (isset($rebuyPaidFlag) ? ", result_paid_rebuy_flag = ?" : "") .
            (isset($addonPaidFlag) ? ", result_paid_addon_flag = ?" : "") .
            (isset($addonFlag) ? ", result_addon_flag = ?" : "") .
            (isset($statusCode) ? ", status_code = ?" : "") .
            (isset($place) ? ", result_place_finished = ?" : "") .
            (isset($playerIdKo) ? ", player_id_ko = ?" : "") .
            " WHERE tournament_id = ?";
            if (isset($playerId)) {
                $sql .= " AND player_id = ?";
            }
        $params = array();
        $paramTypes = array();
        if (isset($rebuyCount)) {
            array_push($params, (int) $rebuyCount);
            array_push($paramTypes, PDO::PARAM_INT);
        }
        if (isset($rebuyPaidFlag)) {
            array_push($params, $rebuyPaidFlag);
            array_push($paramTypes, PDO::PARAM_STR);
        }
        if (isset($addonPaidFlag)) {
            array_push($params, $addonPaidFlag);
            array_push($paramTypes, PDO::PARAM_STR);
        }
        if (isset($addonFlag)) {
            array_push($params, $addonFlag);
            array_push($paramTypes, PDO::PARAM_STR);
        }
        if (isset($statusCode)) {
            array_push($params, $statusCode);
            array_push($paramTypes, PDO::PARAM_STR);
        }
        if (isset($place)) {
            array_push($params, $place);
            array_push($paramTypes, PDO::PARAM_INT);
        }
        if (isset($playerIdKo)) {
            array_push($params, $playerIdKo);
            array_push($paramTypes, PDO::PARAM_INT);
        }
        array_push($params, $tournamentId);
        if (isset($playerId)) {
            array_push($params, $playerId);
            array_push($paramTypes, PDO::PARAM_INT);
        }
        array_push($paramTypes, PDO::PARAM_INT);
        return $this->getEntityManager()->getConnection()->executeStatement(sql: $sql, params: $params, types: $paramTypes);
    }

    public function deleteForTournament(int $tournamentId) {
        return $this->getEntityManager()->getConnection()->executeStatement(sql: "DELETE FROM poker_results WHERE tournament_id = ?", params: [$tournamentId], types: [PDO::PARAM_INT]);
    }

    public function deleteForTournamentAndPlayer(int $tournamentId, int $playerId) {
        return $this->getEntityManager()->getConnection()->executeStatement(sql: "DELETE FROM poker_results WHERE tournament_id = ? AND player_id = ?", params: [$tournamentId, $playerId], types: [PDO::PARAM_INT, PDO::PARAM_INT]);
    }

    public function updateForCancellation(int $tournamentId, int $registerOrder) {
        return $this->getEntityManager()->getConnection()->executeStatement(sql: "UPDATE poker_results SET result_registration_order = result_registration_order - 1 WHERE tournament_id = ? AND result_registration_order > ?", params: [$tournamentId, $registerOrder], types: [PDO::PARAM_INT, PDO::PARAM_INT]);
    }

    public function getMaxRegistrationOrder(int $tournamentId) {
        return $this->createQueryBuilder(alias: "r")
                    ->addSelect(select: "IFNULL(MAX(r.resultRegistrationOrder) + 1, 1) AS resultRegistrationOrderMax")
                    ->where(predicates: "r.tournaments = :tournamentId")
                    ->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "tournamentId", value: $tournamentId))))
                    ->getQuery()->getResult();
    }

    public function getSeasonStats(DateTime $startDate, DateTime $endDate) {
        $sql =
            "SELECT SUBSTRING_INDEX(NAME, ' ', 1) AS first_name, SUBSTRING_INDEX(NAME, ' ', -1) AS last_name, d.tourneys AS '#', IFNULL(d.Points, 0) AS pts, d.Points / d.numTourneys AS AvgPoints, d.FTs AS 'count', d.pctFTs AS '%', d.avgPlace AS 'avg', d.high AS 'best', d.low AS 'worst', -(IF(d.numTourneys IS NULL, 0, d.numTourneys * d.buyinAmount)) AS buyins, -IFNULL(d.rebuys, 0) AS rebuys, -IFNULL(d.addons, 0) AS addons, -(IF(d.numTourneys IS NULL, 0, d.numTourneys * d.buyinAmount)) + -IFNULL(d.rebuys, 0) + -IFNULL(d.addons, 0) AS 'total', d.earnings, d.earnings - IF(d.numTourneys IS NULL, 0, d.numTourneys * d.buyinAmount) - IFNULL(d.rebuys, 0) - IFNULL(d.addons, 0) AS 'net(+/-)', d.kos AS 'KOs', d.kos / d.numTourneys AS 'Avg KO', d.koMax AS 'Most KO', IFNULL(d.wins, 0) AS wins, IFNULL(d.wins, 0) / d.numTourneys AS AvgWins " .
            "FROM (SELECT a.player_id, a.name, a.active, a.Tourneys, a.FTs, a.PctFTs, a.AvgPlace, a.Low, a.High, IFNULL(b.earnings, 0) AS earnings, a.NumTourneys, " .
            "             e.result_place_finished, e.NumPlayers, e.Points, e.Rebuys, e.Addons, e.NumRebuys, e.BuyinAmount, km.kos, km.koMax, w.wins " .
            "      FROM (SELECT p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, IFNULL(nt.NumTourneys, 0) AS Tourneys, IFNULL(nft.NumFinalTables, 0) AS FTs, IF(nt.NumTourneys IS NULL, 0, IFNULL(nft.NumFinalTables, 0) / nt.NumTourneys) AS PctFTs, IF(nt.NumTourneys IS NULL, 0, IFNULL(nt.TotalPlaces, 0) / nt.NumTourneys) AS AvgPlace, IFNULL(nt.MaxPlace, 0) AS Low, IFNULL(nt.MinPlace, 0) AS High, IFNULL(nt.NumTourneys, 0) AS NumTourneys, p.player_active_flag AS active " .
            "            FROM poker_players p LEFT JOIN (SELECT r1.player_id, COUNT(*) AS NumTourneys, SUM(r1.result_place_finished) AS TotalPlaces, MAX(r1.result_place_finished) AS MaxPlace, MIN(r1.result_place_finished) AS MinPlace " .
            "                                         FROM poker_results r1 INNER JOIN poker_tournaments t1 ON r1.tournament_id = t1.tournament_id " .
            "                                         AND t1.tournament_date BETWEEN :startDate1 AND :endDate1 " .
            "                                         WHERE r1.result_place_finished > 0 GROUP BY r1.player_id) nt ON p.player_id = nt.player_id " .
            "            LEFT JOIN (SELECT r2.player_id, COUNT(*) AS NumFinalTables " .
            "                       FROM poker_results r2 INNER JOIN poker_tournaments t2 ON r2.tournament_id = t2.tournament_id " .
            "                       AND t2.tournament_date BETWEEN :startDate2 AND :endDate2 " .
            "                       INNER JOIN poker_seasons se ON t2.tournament_date BETWEEN se.season_start_date AND se.season_end_date " .
            "                       WHERE r2.result_place_finished BETWEEN 1 AND se.season_final_table_players GROUP BY r2.player_id) nft ON p.player_id = nft.player_id) a " .
            "      LEFT JOIN (SELECT player_id, name, SUM(TotalEarnings) AS Earnings, MAX(MaxEarnings) AS MaxEarnings, numTourneys " .
            "                 FROM (SELECT player_id, name, SUM(Earnings) AS TotalEarnings, MAX(Earnings) AS MaxEarnings, NumTourneys " .
            "                       FROM (SELECT p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, ((np.NumPlayers * (t.tournament_buyin_amount - (t.tournament_buyin_amount * t.tournament_rake))) + (IFNULL(nr.NumRebuys, 0) * (t.tournament_rebuy_amount - (t.tournament_rebuy_amount * t.tournament_rake))) + (IFNULL(na.NumAddons, 0) * (t.tournament_addon_amount - (t.tournament_addon_amount * t.tournament_Rake)))) * IFNULL(s.structure_percentage, 0) AS Earnings, nt.NumTourneys " .
            "                             FROM poker_results r INNER JOIN poker_players p ON r.player_id = p.player_id " .
            "                             INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id " .
            "                             AND t.tournament_date BETWEEN :startDate3 AND :endDate3 " .
            "                       INNER JOIN (SELECT r1.player_id, COUNT(*) AS NumTourneys " .
            "                                   FROM poker_results r1 INNER JOIN poker_tournaments t1 ON r1.tournament_id = t1.tournament_id AND r1.result_place_finished > 0 " .
            "                                   AND t1.tournament_date BETWEEN :startDate4 AND :endDate4 " .
            "                                   GROUP BY r1.player_id) nt ON r.player_id = nt.player_id " .
            "                       INNER JOIN (SELECT r2.tournament_id, COUNT(*) AS NumPlayers FROM poker_results r2 WHERE r2.result_place_finished > 0 GROUP BY r2.tournament_id) np ON r.tournament_id = np.tournament_id " .
            "                       LEFT JOIN (SELECT r3.tournament_id, SUM(r3.result_rebuy_count) AS NumRebuys FROM poker_results r3 WHERE r3.result_place_finished > 0 AND r3.result_rebuy_Count > 0 GROUP BY r3.tournament_id) nr ON r.tournament_id = nr.tournament_id " .
            "                       LEFT JOIN (SELECT tournament_id, COUNT(result_paid_addon_flag) AS NumAddons FROM poker_results WHERE result_paid_addon_flag = '" . Constant::FLAG_YES . "' GROUP BY tournament_id) na ON r.tournament_id = na.tournament_id " .
            "                       LEFT JOIN (SELECT a.tournament_id, s1.payout_id, s1.structure_place, s1.structure_percentage " .
            "                                  FROM (SELECT np.tournament_id, p.payout_id " .
            "                                        FROM (SELECT r.tournament_id, COUNT(*) AS numPlayers FROM poker_results r WHERE r.result_place_finished > 0 AND r.status_code IN ('" . Constant::CODE_STATUS_REGISTERED . "', '" . Constant::CODE_STATUS_FINISHED . "') GROUP BY r.tournament_id) np INNER JOIN poker_tournaments t on np.tournament_id = t.tournament_id " .
            "                                        INNER JOIN poker_group_payouts gp ON t.group_id = gp.group_id " .
            "                                        INNER JOIN poker_payouts p ON gp.payout_id = p.payout_id AND np.numPlayers BETWEEN p.payout_min_players AND p.payout_max_players) a " .
            "                                        INNER JOIN poker_structures s1 ON a.payout_id = s1.payout_id) s ON r.tournament_id = s.tournament_id AND r.result_place_finished = s.structure_place " .
            "                                        WHERE r.result_place_finished > 0) y " .
            "                       GROUP BY player_id " .
            "                       UNION ALL " .
            "                       SELECT xx.player_id, xx.name, SUM(xx.earnings) AS TotalEarnings, MAX(xx.earnings) AS MaxEarnings, 0 " .
            "                       FROM (SELECT se.season_start_date, YEAR(t.tournament_date) AS Yr, p.player_id, p.player_first_name, p.player_last_name, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, qq.total * IFNULL(s.structure_percentage, 0) AS Earnings, numTourneys AS trnys " .
            "                             FROM poker_results r INNER JOIN poker_players p ON r.player_id = p.player_id " .
            "                             INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id AND t.tournament_date BETWEEN :startDate5 AND :endDate5 " .
            "                             INNER JOIN poker_seasons se ON t.tournament_Date BETWEEN se.season_start_date AND se.season_end_date " .
            "                             INNER JOIN (SELECT season_start_date, season_end_date, SUM(total) - IF(YEAR(season_end_date) = 2008, 150, IF(YEAR(season_end_date) = 2007, -291, IF(YEAR(season_end_date) = 2006, -824, 0))) AS total " .
            "                                         FROM (SELECT se2.season_start_date, se2.season_end_date, t2.tournament_id AS Id, IF(b.Play IS NULL, 0, CONCAT(b.Play, '+', IFNULL(nr.NumRebuys, 0), 'r', '+', IFNULL(na.NumAddons, 0), 'a')) AS Play, ((t2.tournament_buyin_amount * t2.tournament_rake) * Play) + ((t2.tournament_rebuy_amount * t2.tournament_rake) * IFNULL(nr.NumRebuys, 0)) + ((t2.tournament_addon_amount * t2.tournament_rake) * IFNULL(na.NumAddons, 0) ) AS Total " .
            "                                               FROM poker_tournaments t2 INNER JOIN poker_seasons se2 ON t2.tournament_date BETWEEN se2.season_start_date AND se2.season_end_date " .
            "                                               LEFT JOIN (SELECT tournament_id, COUNT(*) AS Play FROM poker_results WHERE result_paid_buyin_flag = '" . Constant::FLAG_YES . "' AND result_place_finished > 0 GROUP BY tournament_id) b ON t2.tournament_id = b.tournament_id " .
            "                                               LEFT JOIN (SELECT r.tournament_id, SUM(r.result_rebuy_count) AS NumRebuys FROM poker_results r WHERE r.result_paid_buyin_flag = '" . Constant::FLAG_YES . "' AND r.result_rebuy_count > 0 GROUP BY r.tournament_id) nr ON t2.tournament_id = nr.tournament_id " .
            "                                               LEFT JOIN (SELECT r.tournament_id, COUNT(*) AS NumAddons FROM poker_results r WHERE r.result_paid_addon_flag = '" . Constant::FLAG_YES . "' GROUP BY r.tournament_id) na ON t2.tournament_id = na.tournament_id) zz " .
            "                                         GROUP BY season_start_date, season_end_date) qq ON qq.season_start_date = se.season_start_date AND qq.season_end_date = se.season_end_date " .
            "                             LEFT JOIN poker_special_types st ON t.special_type_id = st.special_type_id " .
            "                             INNER JOIN (SELECT r1.player_id, COUNT(*) AS NumTourneys FROM poker_results r1 INNER JOIN poker_tournaments t1 ON r1.tournament_id = t1.tournament_id AND r1.result_place_finished > 0 INNER JOIN poker_special_types st1 ON t1.special_type_id = st1.special_type_id AND st1.special_type_description = '" . Constant::DESCRIPTION_CHAMPIONSHIP . "' GROUP BY r1.player_id) nt ON r.player_id = nt.player_id " .
            "                             LEFT JOIN (SELECT a.tournament_id, s1.payout_id, s1.structure_place, s1.structure_percentage " .
            "                                        FROM (SELECT np.tournament_id, p.payout_id " .
            "                                              FROM (SELECT r.tournament_id, COUNT(*) AS numPlayers FROM poker_results r WHERE r.result_place_finished > 0 AND r.status_code IN ('" . Constant::CODE_STATUS_REGISTERED . "','" . Constant::CODE_STATUS_FINISHED . "') GROUP BY r.tournament_id) np " .
            "                                              INNER JOIN poker_tournaments t on np.tournament_id = t.tournament_id " .
            "                                              AND t.tournament_date BETWEEN :startDate6 AND :endDate6 " .
            "                                              INNER JOIN poker_group_payouts gp ON t.group_id = gp.group_id " .
            "                                              INNER JOIN poker_payouts p ON gp.payout_id = p.payout_id AND np.numPlayers BETWEEN p.payout_min_players AND p.payout_max_players) a " .
            "                                              INNER JOIN poker_structures s1 ON a.payout_id = s1.payout_id) s ON r.tournament_id = s.tournament_id AND r.result_place_finished = s.structure_place " .
            "                             WHERE r.result_place_finished > 0 " .
            "                             AND st.special_type_description = '" . Constant::DESCRIPTION_CHAMPIONSHIP . "' " .
            "                             GROUP BY player_id, yr) xx " .
            "                       GROUP BY xx.player_id, xx.name) cc " .
            "                 GROUP BY player_id, name) b ON a.player_id = b.player_id " .
            "      LEFT JOIN (SELECT c.player_id, c.result_place_finished, c.NumPlayers, IF(c.result_place_finished IS NULL, 0, SUM((c.numPlayers - c.result_place_finished + 1) * IFNULL(c.special_type_multiplier, 1))) + IF(c.result_place_finished BETWEEN 1 AND c.season_final_table_players, c.season_final_table_bonus_points, 0) AS Points, SUM(IFNULL(c.NumRebuys, 0) * c.tournament_rebuy_amount) AS Rebuys, SUM(IFNULL(c.NumAddons, 0) * c.tournament_addon_amount) AS Addons, IFNULL(c.NumRebuys, 0) AS NumRebuys, c.BuyinAmount " .
            "                 FROM (SELECT a.tournament_id, a.tournament_description, a.player_id, a.result_place_finished, a.NumPlayers, a.NumRebuys, a.tournament_buyin_amount AS BuyinAmount, a.tournament_rebuy_amount, a.tournament_addon_amount, a.NumAddons, a.special_type_description, a.special_type_multiplier, a.season_final_table_players, a.season_final_table_bonus_points " .
            "                       FROM (SELECT r.tournament_id, t.tournament_description, r.player_id, r.result_place_finished, np.NumPlayers, nr.NumRebuys, t.tournament_buyin_amount, t.tournament_rebuy_amount, t.tournament_addon_amount, na.NumAddons, st.special_type_description, st.special_type_multiplier, se.season_final_table_players, se.season_final_table_bonus_points " .
            "                             FROM poker_results r INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id AND t.tournament_date BETWEEN :startDate7 AND :endDate7 " .
            "                             INNER JOIN poker_seasons se ON t.tournament_date BETWEEN se.season_start_date AND se.season_end_date " .
            "                             AND r.result_place_finished > 0 " .
            "                             LEFT JOIN poker_special_types st ON t.special_type_id = st.special_type_id " .
            "                             INNER JOIN (SELECT r3.tournament_id, COUNT(*) AS NumPlayers FROM poker_results r3 INNER JOIN poker_tournaments t3 ON r3.tournament_id = t3.tournament_id " .
            "                                         AND t3.tournament_date BETWEEN :startDate8 AND :endDate8 " .
            "                                         WHERE r3.result_place_finished > 0 GROUP BY r3.tournament_id) np ON r.tournament_id = np.tournament_id " .
            "                             LEFT JOIN (SELECT r4.tournament_id, r4.player_id, SUM(r4.result_rebuy_count) AS NumRebuys FROM poker_results r4 INNER JOIN poker_tournaments t4 ON r4.tournament_id = t4.tournament_id " .
            "                                        AND t4.tournament_date BETWEEN :startDate9 AND :endDate9 " .
            "                                        WHERE r4.result_place_finished > 0 AND r4.result_rebuy_count > 0 GROUP BY r4.tournament_id, r4.player_id) nr ON r.tournament_id = nr.tournament_id AND r.player_id = nr.player_id " .
            "                             LEFT JOIN (SELECT tournament_id, player_id, COUNT(result_paid_addon_flag) AS NumAddons FROM poker_results r7 WHERE result_paid_addon_flag = '" . Constant::FLAG_YES . "' GROUP BY tournament_id, player_id) na ON r.tournament_id = na.tournament_id AND r.player_id = na.player_id) a " .
            "                      ) c " .
            "                 GROUP BY c.player_id) e ON b.player_id = e.player_id " .
            "      LEFT JOIN (SELECT player_id, SUM(knockouts) AS kos, MAX(knockouts) AS koMax " .
            "                 FROM (SELECT p.player_id, COUNT(*) AS knockouts " .
            "                       FROM poker_tournaments t INNER JOIN poker_results r ON t.tournament_id = r.tournament_id " .
            "                       AND t.tournament_date BETWEEN :startDate10 AND :endDate10 " .
            "                       AND r.result_place_finished > 0 " .
            "                       INNER JOIN poker_players p ON r.player_id_ko = p.player_id " .
            "                       GROUP BY t.tournament_id, r.player_id_ko) k " .
            "                 GROUP BY player_id) km ON b.player_id = km.player_id " .
            "      LEFT JOIN (SELECT r.player_id, COUNT(*) AS wins " .
            "                 FROM poker_tournaments t INNER JOIN poker_results r ON t.tournament_id = r.tournament_id AND r.result_place_finished = " . Constant::FLAG_YES_DATABASE .
            "                 AND t.tournament_date BETWEEN :startDate11 AND :endDate11 " .
            "                 GROUP BY r.player_id) w ON b.player_id = w.player_id " .
            "      WHERE b.player_id IN (SELECT DISTINCT player_id FROM poker_results WHERE status_code = '" . Constant::CODE_STATUS_FINISHED . "') " .
            "      ) d";
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        if (isset($startDate) && isset($endDate)) {
            $startDateFormatted = DateTimeUtility::formatDatabaseDate(value: $startDate);
            $endDateFormatted = DateTimeUtility::formatDatabaseDate(value: $endDate);
            $statement->bindValue(param: "startDate1", value: $startDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "endDate1", value: $endDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "startDate2", value: $startDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "endDate2", value: $endDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "startDate3", value: $startDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "endDate3", value: $endDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "startDate4", value: $startDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "endDate4", value: $endDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "startDate5", value: $startDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "endDate5", value: $endDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "startDate6", value: $startDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "endDate6", value: $endDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "startDate7", value: $startDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "endDate7", value: $endDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "startDate8", value: $startDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "endDate8", value: $endDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "startDate9", value: $startDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "endDate9", value: $endDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "startDate10", value: $startDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "endDate10", value: $endDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "startDate11", value: $startDateFormatted, type: PDO::PARAM_STR);
            $statement->bindValue(param: "endDate11", value: $endDateFormatted, type: PDO::PARAM_STR);
        }
        return $statement->executeQuery()->fetchAllAssociative();
    }
}