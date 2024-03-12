<?php
declare(strict_types = 1);
namespace ccp;
use Poker\Ccp\Model\Constant;
use Poker\Ccp\Utility\SessionUtility;
require_once "init.php";
$smarty->assign("title", "Chip Chair and a Prayer Championship");
$smarty->assign("formName", "frmChampionship");
$smarty->assign("action", $_SERVER["SCRIPT_NAME"]);
$smarty->assign("heading", "Championship");
$smarty->assign("style", "");
$output = "";
$startDate = SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_START_DATE);
$endDate = SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_END_DATE);
$resultList = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getPrizePool(startDate: $startDate, endDate: $endDate);
if (0 < count($resultList)) {
    $prizePool = $resultList[0]["total"];
}
$resultList = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getWins(startDate: $startDate, endDate: $endDate, playerId: NULL, winsForPlayer: false, winsForSeason: true, rank: false, orderBy: array(), indexed: false);
if (0 < count($resultList)) {
    $ctr = 0;
    while ($ctr < count($resultList)) {
        $aryWinners[$ctr] = $resultList[$ctr]["name"];
        $ctr++;
    }
  // $caption = "<strong>There are " . $ctr . " winners</strong>";
  // $hideColIndexes = array(0,2);
}
// echo "<BR>winners -> " . print_r($aryWinners);
$countWinners = isset($aryWinners) ? count($aryWinners) : 0;
$aryAbsentIds = array();
$aryAbsentNames = array();
$resultList = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getTournamentAbsences($startDate);
$ctr = - 1;
foreach ($resultList as $players) {
    $ctr++;
    $aryAbsentIds[$ctr] = $players->getPlayerId();
    $aryAbsentNames[$ctr] = $players->getPlayerName();
}
$aryPosition = array();
$aryPosition[0] = 1;
$aryPosition[1] = 9;
$aryPosition[2] = 8;
$aryPosition[3] = 7;
$aryPosition[4] = 6;
$aryPosition[5] = 5;
$aryPosition[6] = 4;
$aryPosition[7] = 3;
$aryPosition[8] = 2;
$resultList = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getChampionshipQualified(startDate: $startDate, endDate: $endDate, numTourneys: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_CHAMPIONSHIP_QUALIFY), indexed: false);
$count = count(value: $resultList) - count(value: $aryAbsentIds);
if (0 < $count) {
  // $numPlayers = $count;
    $ctr = 0;
    $index = 0;
    $allowCtr = 0;
    $absentCtr = 0;
    $additionalPlayers = 36 - $countWinners;
    while ($ctr < count(value: $resultList)) {
        $aryResult = $resultList[$ctr];
        // echo "<br>".$ctr."-->".$index."->".$aryResult[1];
        if (in_array($aryResult["name"], $aryAbsentNames)) {
            foreach ($aryAbsentNames as $aryAbsentName) {
            // echo "<Br>" . $aryResult[1] . " == " . $aryAbsentName;
                if ($aryResult["name"] == $aryAbsentName) {
                  // $aryAbsentSeeds[$absentCtr] = $ctr + 1;
                    $aryAbsentSeeds[$aryAbsentName] = $ctr + 1;
                    $absentCtr ++;
                }
            }
        } else {
            $index++;
          // echo "<br>".$allowCtr . " <= " . $additionalPlayers;
            if ($allowCtr <= $additionalPlayers) {
            // if winner add * else increment additional players
                if (in_array($aryResult["name"], $aryWinners)) {
                    $aryResult["name"] = "*" . $aryResult["name"];
                } else {
                    $allowCtr++;
                }
            }
          // echo "<br>setting aryNames[" . $index . "] = " . $aryResult[1];
            $aryNames[$index] = $aryResult["name"];
          // echo "<br>setting aryAvgPts[" . $index . "] = " . $aryResult[2];
            $aryPts[$index] = $aryResult["points"];
      }
      $ctr++;
    }
  // sort makes array index start at 0 so adjust counter
  // $ctr--;
    $index--;
    array_multisort($aryPts, SORT_DESC, $aryNames, SORT_ASC);
  // echo "<BR>names -> " . print_r($aryNames, true);
  // echo "<BR>pts -> " . print_r($aryPts, true);
  // echo "<br>seeds -> " . print_r($aryAbsentSeeds, true);
  // if (27 >= count($aryNames)) {
  // $numTables = 3;
  // $totalPlayers = 27;
  // } else {
    $numTables = 4;
    $totalPlayers = 36;
  // }
    $playerCount = $totalPlayers;
    $maxIndex = $numTables;
    while ($index < ($totalPlayers)) {
        $index++;
        $playerCount--;
        // echo "<br>setting aryNames[".$index."] = EMPTY";
        $aryNames[$index] = "EMPTY";
    //     echo "<br>setting aryMaxPlayers[".$maxIndex."] = " . ($playerCount / $numTables) . " -> " . floor($playerCount / $numTables);
        $aryMaxPlayers[$maxIndex] = floor($playerCount / $numTables);
        if ($maxIndex == 1) {
            $maxIndex = $numTables;
        } else {
            $maxIndex--;
        }
    }
      $topThird = ceil(num: $playerCount / 3);
      $topThirdChipCount = 11000;
      $middleThird = floor(num: $playerCount / 3);
      $middleThirdChipCount = 10000;
      $bottomThird = floor(num: $playerCount / 3);
      $bottomThirdChipCount = 9000;
      $ctr = 0;
    while ($ctr < count(value: $aryNames)) {
        if ($ctr < $topThird) {
            $aryNames[$ctr] .= " (" . $topThirdChipCount . " chips)";
        } else if ($ctr < ($topThird + $middleThird)) {
            $aryNames[$ctr] .= " (" . $middleThirdChipCount . " chips)";
        } else {
            if ($aryNames[$ctr] != "EMPTY") {
                $aryNames[$ctr] .= " (" . $bottomThirdChipCount . " chips)";
            }
        }
        $ctr++;
    }
    $maxPlayers = round(num: count(value: $aryNames) / $numTables); // ceil(count($aryNames) / $numTables);
                                                                  // $numEmpty = $totalPlayers - count($aryNames);
    $tableNumber = 0;
    $index = 1;
    $ctr = 0;
    $table = [];
    while ($ctr < $totalPlayers) {
        $tableNumber ++;
        // echo "<br>Adding " . $aryNames[$ctr] . " at table[" . $tableNumber . "][" . $index . "][0] from overall aryNames[" . $ctr . "]";
        $table[$tableNumber][$index][0] = $aryNames[$ctr];
        if ($tableNumber == $numTables) {
            $tableNumber = 0;
            $index++;
        }
        $ctr++;
    }
  // echo print_r($table) . "<br>";
  // skip first entry
    $ctr = 1;
    while ($ctr <= count(value: $table)) {
        // echo "<br>Setting table[" . $ctr . "][1][1] = 1";
        $table[$ctr][1][1] = "1 (Dealer)";
        $position = $maxPlayers;
        $ctr2 = 2;
        $shortTable = false;
        $checkCtr = $ctr2;
        // adjust blinds for empty seat at table
        if ($table[$ctr][count(value: $table[$ctr])][0] == "EMPTY") {
          // echo "<br>Setting short table flag";
            $shortTable = true;
        }
        while ($ctr2 <= count(value: $table[$ctr])) {
          // echo "<br>Setting table[" . $ctr . "][" . $ctr2 . "][1] = " . $position;
            $table[$ctr][$ctr2][1] = $position;
          // if short table adjust check by 1
            if ($shortTable) {
                $checkCtr = $ctr2;
            }
          // echo "<br>Checking " . $checkCtr . " == " . ($maxPlayers - 1);
          // if ($checkCtr == ($maxPlayers - 1)) {
            if ($checkCtr == ($aryMaxPlayers[$ctr] - 1)) {
                $table[$ctr][$ctr2][1] .= " (Small Blind)";
            // } else if ($checkCtr == ($maxPlayers)) {
            } else if ($checkCtr == ($aryMaxPlayers[$ctr])) {
                $table[$ctr][$ctr2][1] .= " (Big Blind)";
            }
            $position--;
            $ctr2++;
            $checkCtr++;
        }
        $ctr++;
    }
    $output .= "    <h3>Total payout of $" . number_format(num: (float) $prizePool, decimals: 0) . "</h3>\n";
    $output .= "    <h4>Exact amounts below subject to change</h4>\n";
    $output .= "    <div class=\"column\"><strong>Top " . $topThird . "</strong></div>\n";
    $output .= "    <div class=\"column\"><strong>Middle " . $middleThird . "</strong></div>\n";
    $output .= "    <div class=\"column\"><strong>Bottom " . $bottomThird . "</strong></div>\n";
    $output .= "    <div class=\"clear\"></div>\n";
    $output .= "    <div class=\"column\">" . $topThirdChipCount . " chips</div>\n";
    $output .= "    <div class=\"column\">" . $middleThirdChipCount . " chips</div>\n";
    $output .= "    <div class=\"column\">" . $bottomThirdChipCount . " chips</div>\n";
    $output .= "    <div class=\"clear\"></div>\n";
    $output .= "    <div class=\"column\"><strong><i>Position<br />(% of total)</i></strong></div>\n";
    $output .= "    <div class=\"column\"><strong><i>Payout</i></strong></div>\n";
    $output .= "    <div class=\"clear\"></div>\n";
    $resultList = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getChampionshipPayout();
//     $resultList = $entityManager->getRepository(Constant::ENTITY_GROUP_PAYOUTS)->getById(groupId: 9, payoutId: 11);
    $resultList = $entityManager->getRepository(Constant::ENTITY_GROUP_PAYOUTS)->getById(groupId: $resultList[0]["group_id"], payoutId: $resultList[0]["payout_id"]);
    if (0 < count(value: $resultList)) {
        $ctr = 0;
        while ($ctr < count(value: $resultList)) {
            $groupPayout = $resultList[$ctr];
            $payouts = $groupPayout->getPayouts();
            $structures = $payouts->getStructures();
            foreach ($structures as $structure) {
                $output .= "    <div class=\"column\">" . $structure->getStructurePlace() . " (" . $structure->getStructurePercentage() * 100 . "%)</div>\n";
                $output .= "    <div class=\"column\">$" . round(num: $prizePool * $structure->getStructurePercentage(), precision: 0, mode: PHP_ROUND_HALF_UP) . "</div>\n";
                $output .= "    <div class=\"clear\"></div>\n";
            }
            $ctr++;
        }
  }
    $output .= "    <br />\n";
    $output .= "    <div class=\"heading\"><strong>Unable to attend</strong></div>\n";
    $output .= "    <div class=\"column\"><strong><i>Name</i></strong></div>\n";
    $output .= "    <div><strong><i>Original seat</i></strong></div>\n";
    $output .= "    <div class=\"clear\"></div>\n";
    $ctr = 0;
  // echo "<br>aryAbsentNames " . print_r($aryAbsentNames, true);
  // echo "<br>aryAbsentSeeds " . print_r($aryAbsentSeeds, true);
  // echo print_r($aryPosition, true);
    foreach ($aryAbsentNames as $absentName) {
        $output .= "    <div class=\"column\">" . $absentName . "</div>\n";
        $tableNum = ($aryAbsentSeeds[$absentName] % $numTables) == 0 ? 4 : ($aryAbsentSeeds[$absentName] % $numTables);
        $positionNum = ($aryAbsentSeeds[$absentName] % $numTables) == 0 ? $aryPosition[floor($aryAbsentSeeds[$absentName] / $numTables) - 1] : $aryPosition[floor($aryAbsentSeeds[$absentName] / $numTables)];
        // echo "<br>".$absentName ." -> " .$aryAbsentSeeds[$absentName]." --> " . ($aryAbsentSeeds[$absentName] / $numTables);
        $output .= "    <div class=\"column2\">Table " . $tableNum . " Position " . $positionNum . "</div>\n";
        $output .= "    <div class=\"clear\"></div>\n";
        $ctr++;
    }
    $output .= "    <br />\n";
    $output .= "    <div class=\"heading\"><strong>* represent winners</strong></div>\n";
    $ctr = 0;
    while ($ctr < count(value: $table)) {
        $ctr++;
        $ctr2 = 0;
        while ($ctr2 < count(value: $table[$ctr])) {
            $ctr2++;
            $output .= "    <div class=\"clear\"></div>\n";
            if ($ctr2 == 1) {
                $output .= "    <div class=\"heading\"><strong>Table $ctr</strong></div>\n";
                $output .= "    <div class=\"column\"><strong><i>Position</i></strong></div>\n";
                $output .= "    <div class=\"column2\"><strong><i>Name</i></strong></div>\n";
                $output .= "    <div class=\"clear\"></div>\n";
            }
            $output .= "    <div class=\"column\">" . $table[$ctr][$ctr2][1] . "</div>\n";
            $output .= "    <div class=\"column2\">" . (isset($table[$ctr][$ctr2]) ? $table[$ctr][$ctr2][0] : "") . "</div>\n";
            $output .= "    <div class=\"clear\"></div>\n";
        }
    }
} else {
    $output .= "<div class=\"center\">No one has qualified with at least " . SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_CHAMPIONSHIP_QUALIFY) . " tournaments yet or " . SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_CHAMPIONSHIP_QUALIFY) . " tournaments have not been completed for " . date(format: "Y") . "</div>\n";
}
$smarty->assign("content", $output);
$smarty->display("championship.tpl");