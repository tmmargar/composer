<?php
namespace Poker\Ccp\Entity;
use Doctrine\ORM\EntityRepository;
use Poker\Ccp\Model\Constant;
class BaseRepository extends EntityRepository {
    // $query is query to modify
    // $whereClause is where clause to replace in query
    // $selectFieldName is field name used for ranking
    // $selectFieldNames is list of field names to search in
    // $orderByFieldName is order by field name to use when replacing
    protected function modifyQueryAddRank(string $query, string $whereClause, string $selectFieldName, string $selectFieldNames, string $orderByFieldName): string {
        $queryTemp = substr_replace(string: $query, replace: "SELECT ROW_NUMBER() OVER (ORDER BY " . $selectFieldName . " DESC, name) AS row, RANK() OVER (ORDER BY " . $selectFieldName . " DESC) AS rank, " . $selectFieldNames . " FROM (SELECT ", offset: 0, length: 6);
//         echo "<br>q=".$queryTemp;
//         echo "<br>w=".$whereClause;
//         echo "<br>s=".$selectFieldName;
        $queryTemp = str_replace(search: $whereClause, replace: " ORDER BY " . $selectFieldName . " DESC, player_last_name, player_first_name) z ORDER BY row, name", subject: $queryTemp);
        return $queryTemp;
    }
    // returns array of query and array of parameters to bind
    protected function buildChampionship(array $params): array {
        $bindParams = NULL;
        $query =
            "SELECT se.season_start_date, YEAR(t.tournament_date) AS yr, p.player_id, p.player_first_name, p.player_last_name, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, " .
            "       qq.total * IFNULL(s.structure_percentage, 0) AS earnings, numTourneys AS trnys " .
            "FROM poker_results r " .
            "INNER JOIN poker_players p ON r.player_id = p.player_id " .
            "INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id ";
        if (isset($params[0]) && isset($params[1])) {
            $query .= "            AND t.tournament_date BETWEEN :startDate91 AND :endDate91 ";
            $bindParams[':startDate91'] = $params[0];
            $bindParams[':endDate91'] = $params[1];
        }
        $query .=
            "INNER JOIN poker_seasons se ON t.tournament_date BETWEEN se.season_start_date AND se.season_end_date " .
            "INNER JOIN (SELECT season_start_date, season_end_date, SUM(total) - IF(YEAR(season_end_date) = 2008, 150, IF(YEAR(season_end_date) = 2007, -291, IF(YEAR(season_end_date) = 2006, -824, 0))) AS total " .
            "            FROM (SELECT se2.season_start_date, se2.season_end_date, t2.tournament_id AS Id, IF(b.Play IS NULL, 0, CONCAT(b.Play, '+', IFNULL(nr.NumRebuys, 0) , 'r', '+', IFNULL(na.NumAddons, 0) , 'a')) AS Play, ((t2.tournament_buyin_amount * t2.tournament_Rake) * Play) + ((t2.tournament_rebuy_amount * t2.tournament_rake) * IFNULL(nr.NumRebuys, 0) ) + ((t2.tournament_addon_amount * t2.tournament_rake) * IFNULL(na.NumAddons, 0) ) AS Total " .
            "                  FROM poker_tournaments t2 " .
            "                  INNER JOIN poker_seasons se2 ON t2.tournament_date BETWEEN se2.season_start_date AND se2.season_end_date " .
            "                  LEFT JOIN (SELECT tournament_id, COUNT(*) AS Play FROM poker_results WHERE result_paid_buyin_flag = '" . Constant::FLAG_YES .
            "' AND result_place_finished > 0 GROUP BY tournament_id) b ON t2.tournament_id = b.tournament_id " .
            "                  LEFT JOIN (SELECT r.tournament_id, SUM(r.result_rebuy_count) AS NumRebuys FROM poker_results r WHERE r.result_paid_rebuy_flag = '" . Constant::FLAG_YES .
            "' AND r.result_rebuy_count > 0 GROUP BY r.tournament_id) nr ON t2.tournament_id = nr.tournament_id " .
            "                  LEFT JOIN (SELECT r.tournament_id, COUNT(*) AS NumAddons FROM poker_results r WHERE r.result_paid_addon_flag = '" . Constant::FLAG_YES .
            "' GROUP BY r.tournament_id) na ON t2.tournament_id = na.tournament_id) zz " .
            "            GROUP BY season_start_date, season_end_date) qq ON qq.season_start_date = se.season_start_date AND qq.season_end_date = se.season_end_date " .
            "LEFT JOIN poker_special_types st ON t.special_type_id = st.special_type_id " .
            "INNER JOIN (SELECT r1.player_id, COUNT(*) AS NumTourneys FROM poker_results r1 INNER JOIN poker_tournaments t1 ON r1.tournament_id = t1.tournament_id AND r1.result_place_finished > 0 INNER JOIN poker_special_types st1 ON t1.special_type_id = st1.special_type_id AND st1.special_type_description = '" . Constant::DESCRIPTION_CHAMPIONSHIP . "' GROUP BY r1.player_id) nt ON r.player_id = nt.player_id " .
            "LEFT JOIN (SELECT a.tournament_id, s1.payout_id, s1.structure_place, s1.structure_percentage " .
            "          FROM (SELECT np.tournament_id, p.payout_id " .
            "                FROM (SELECT r.tournament_id, COUNT(*) AS numPlayers FROM poker_results r WHERE r.result_place_finished > 0 AND r.status_code IN ('" . Constant::CODE_STATUS_REGISTERED . "','" . Constant::CODE_STATUS_FINISHED . "') GROUP BY r.tournament_id) np " .
            "                INNER JOIN poker_tournaments t on np.tournament_id = t.tournament_id ";
        if (isset($params[0]) && isset($params[1])) {
            $query .= "            AND t.tournament_date BETWEEN :startDate92 AND :endDate92 ";
            $bindParams[':startDate92'] = $params[0];
            $bindParams[':endDate92'] = $params[1];
        }
        $query .=
            "                INNER JOIN poker_group_payouts gp ON t.group_id = gp.group_id " .
            "                INNER JOIN poker_payouts p ON gp.payout_id = p.payout_id AND np.numPlayers BETWEEN p.payout_min_players AND p.payout_max_players) a " .
            "          INNER JOIN poker_structures s1 ON a.payout_id = s1.payout_id) s ON r.tournament_id = s.tournament_id AND r.result_place_finished = s.structure_place " .
            "WHERE r.result_place_finished > 0 " . "AND st.special_type_description = '" . Constant::DESCRIPTION_CHAMPIONSHIP . "' ";
        return array($query, $bindParams);
    }
}