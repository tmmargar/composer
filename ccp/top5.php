<?php
declare(strict_types = 1);
namespace ccp;
use DateTime;
use PDO;
use Poker\Ccp\classes\model\Constant;
use Poker\Ccp\classes\model\FormControl;
use Poker\Ccp\classes\model\HtmlTable;
use Poker\Ccp\classes\model\GameType;
use Poker\Ccp\classes\model\Group;
use Poker\Ccp\classes\model\GroupPayout;
use Poker\Ccp\classes\model\LimitType;
use Poker\Ccp\classes\model\Location;
use Poker\Ccp\classes\model\Payout;
use Poker\Ccp\classes\model\Player;
use Poker\Ccp\classes\model\SpecialType;
use Poker\Ccp\classes\model\Structure;
use Poker\Ccp\classes\model\Tournament;
use Poker\Ccp\classes\utility\HtmlUtility;
use Poker\Ccp\classes\utility\SessionUtility;
use Poker\Ccp\classes\utility\DateTimeUtility;
require_once "init.php";
if (!defined("REPORT_ID_PARAM_NAME")) {
    define("REPORT_ID_PARAM_NAME", "reportId");
}
if (!defined("PLAYER_ID_PARAM_NAME")) {
    define("PLAYER_ID_PARAM_NAME", "playerId");
}
if (!defined("NAVIGATION_PARAM_NAME")) {
    define("NAVIGATION_PARAM_NAME", "navigation");
}
if (!defined("PRIZE_POOL_FOR_SEASON")) {
    define("PRIZE_POOL_FOR_SEASON", "prizePoolForSeason");
}
if (!defined("POINTS_TOTAL_FOR_SEASON")) {
    define("POINTS_TOTAL_FOR_SEASON", "pointsTotalForSeason");
}
if (!defined("POINTS_TOTAL_FOR_SEASON_FOR_USER")) {
    define("POINTS_TOTAL_FOR_SEASON_FOR_USER", "pointsTotalForSeasonForPlayer");
}
if (!defined("POINTS_TOTAL_FOR_USER")) {
    define("POINTS_TOTAL_FOR_USER", "pointsTotalForPlayer");
}
if (!defined("POINTS_AVERAGE_FOR_SEASON")) {
    define("POINTS_AVERAGE_FOR_SEASON", "pointsAverageForSeason");
}
if (!defined("POINTS_AVERAGE_FOR_SEASON_FOR_USER")) {
    define("POINTS_AVERAGE_FOR_SEASON_FOR_USER", "pointsAverageForSeasonForPlayer");
}
if (!defined("POINTS_AVERAGE_FOR_USER")) {
    define("POINTS_AVERAGE_FOR_USER", "pointsAverageForPlayer");
}
if (!defined("KNOCKOUTS_TOTAL_FOR_SEASON")) {
    define("KNOCKOUTS_TOTAL_FOR_SEASON", "knockoutsTotalForSeason");
}
if (!defined("KNOCKOUTS_TOTAL_FOR_SEASON_FOR_USER")) {
    define("KNOCKOUTS_TOTAL_FOR_SEASON_FOR_USER", "knockoutsTotalForSeasonForPlayer");
}
if (!defined("KNOCKOUTS_TOTAL_FOR_USER")) {
    define("KNOCKOUTS_TOTAL_FOR_USER", "knockoutsTotalForPlayer");
}
if (!defined("KNOCKOUTS_AVERAGE_FOR_SEASON")) {
    define("KNOCKOUTS_AVERAGE_FOR_SEASON", "knockoutsAverageForSeason");
}
if (!defined("KNOCKOUTS_AVERAGE_FOR_SEASON_FOR_USER")) {
    define("KNOCKOUTS_AVERAGE_FOR_SEASON_FOR_USER", "knockoutsAverageForSeasonForPlayer");
}
if (!defined("KNOCKOUTS_AVERAGE_FOR_USER")) {
    define("KNOCKOUTS_AVERAGE_FOR_USER", "knockoutsAverageForPlayer");
}
if (!defined("EARNINGS_TOTAL_FOR_SEASON")) {
    define("EARNINGS_TOTAL_FOR_SEASON", "earningsTotalForSeason");
}
if (!defined("EARNINGS_TOTAL_FOR_SEASON_FOR_USER")) {
    define("EARNINGS_TOTAL_FOR_SEASON_FOR_USER", "earningsTotalForSeasonForPlayer");
}
if (!defined("EARNINGS_TOTAL_FOR_USER")) {
    define("EARNINGS_TOTAL_FOR_USER", "earningsTotalForPlayer");
}
if (!defined("EARNINGS_AVERAGE_FOR_SEASON")) {
    define("EARNINGS_AVERAGE_FOR_SEASON", "earningsAverageForSeason");
}
if (!defined("EARNINGS_AVERAGE_FOR_SEASON_FOR_USER")) {
    define("EARNINGS_AVERAGE_FOR_SEASON_FOR_USER", "earningsAverageForSeasonForPlayer");
}
if (!defined("EARNINGS_AVERAGE_FOR_USER")) {
    define("EARNINGS_AVERAGE_FOR_USER", "earningsAverageForPlayer");
}
if (!defined("WINNERS_FOR_SEASON")) {
    define("WINNERS_FOR_SEASON", "winnersForSeason");
}
if (!defined("WINS_FOR_USER")) {
    define("WINS_FOR_USER", "winsForPlayer");
}
if (!defined("WINS_TOTAL_FOR_SEASON_FOR_USER")) {
    define("WINS_TOTAL_FOR_SEASON_FOR_USER", "winsTotalForSeasonForPlayer");
}
if (!defined("WINS_AVERAGE_FOR_SEASON_FOR_USER")) {
    define("WINS_AVERAGE_FOR_SEASON_FOR_USER", "winsAverageForSeasonForPlayer");
}
if (!defined("NEMESIS_FOR_USER")) {
    define("NEMESIS_FOR_USER", "nemesisForPlayer");
}
if (!defined("BULLY_FOR_USER")) {
    define("BULLY_FOR_USER", "bullyForPlayer");
}
if (!defined("TOURNAMENTS_WON_FOR_USER")) {
    define("TOURNAMENTS_WON_FOR_USER", "tournamentsWonForPlayer");
}
if (!defined("FINISHES_FOR_USER")) {
    define("FINISHES_FOR_USER", "finishesForPlayer");
}
if (!defined("TOURNAMENTS_PLAYED_FOR_USER")) {
    define("TOURNAMENTS_PLAYED_FOR_USER", "tournamentsPlayedForPlayer");
}
if (!defined("TOURNAMENTS_PLAYED_BY_TYPE_FOR_USER")) {
    define("TOURNAMENTS_PLAYED_BY_TYPE_FOR_USER", "tournamentsPlayedByTypeForPlayer");
}
if (!defined("TOURNAMENTS_PLAYED_FIRST_FOR_USER")) {
    define("TOURNAMENTS_PLAYED_FIRST_FOR_USER", "tournamentsPlayedFirstForPlayer");
}
$smarty->assign("title", "Chip Chair and a Prayer Top 5");
$smarty->assign("heading", "");
if (!isset($reportId)) {
    $reportId = (isset($_POST[REPORT_ID_PARAM_NAME]) ? $_POST[REPORT_ID_PARAM_NAME] : isset($_GET[REPORT_ID_PARAM_NAME])) ? $_GET[REPORT_ID_PARAM_NAME] : "";
}
if (!isset($parentObjectId)) {
    $output = "<div class=\"contentTop5\">\n";
} else {
    $output = "";
}
$playerId = (int) ((isset($_POST[PLAYER_ID_PARAM_NAME]) ? $_POST[PLAYER_ID_PARAM_NAME] : isset($_GET[PLAYER_ID_PARAM_NAME])) ? $_GET[PLAYER_ID_PARAM_NAME] : SessionUtility::getValue(SessionUtility::OBJECT_NAME_USERID));
if (!isset($navigation)) {
    $navigation = (isset($_POST[NAVIGATION_PARAM_NAME]) ? $_POST[NAVIGATION_PARAM_NAME] : isset($_GET[NAVIGATION_PARAM_NAME])) ? $_GET[NAVIGATION_PARAM_NAME] : NULL;
}
if (isset($navigation)) {
    $smarty->assign("style", "");
    $smarty->assign("formName", "frmTop5");
    $smarty->assign("action", $_SERVER["SCRIPT_NAME"]);
}
if (!isset($parentObjectId)) {
    $parentObjectId = "container";
}
$classNames = array();
switch ($reportId) {
    case PRIZE_POOL_FOR_SEASON:
        $title = "Prize pool for season";
        break;
    case POINTS_TOTAL_FOR_SEASON:
        $title = "Total points for season";
        break;
    case POINTS_TOTAL_FOR_SEASON_FOR_USER:
        $title = "Total points for season for player";
        break;
    case POINTS_TOTAL_FOR_USER:
        $title = "Total points for player";
        break;
    case POINTS_AVERAGE_FOR_SEASON:
        $title = "Average points for season";
        break;
    case POINTS_AVERAGE_FOR_SEASON_FOR_USER:
        $title = "Average points for season for player";
        break;
    case POINTS_AVERAGE_FOR_USER:
        $title = "Average points for player";
        break;
    case EARNINGS_TOTAL_FOR_SEASON:
        $title = "Total earnings for season";
        break;
    case EARNINGS_TOTAL_FOR_SEASON_FOR_USER:
        $title = "Total earnings for season for player";
        break;
    case EARNINGS_TOTAL_FOR_USER:
        $title = "Total earnings for player";
        break;
    case EARNINGS_AVERAGE_FOR_SEASON:
        $title = "Average earnings for season";
        break;
    case EARNINGS_AVERAGE_FOR_SEASON_FOR_USER:
        $title = "Average earnings for season for player";
        break;
    case EARNINGS_AVERAGE_FOR_USER:
        $title = "Average earnings for player";
        break;
    case KNOCKOUTS_AVERAGE_FOR_SEASON:
        $title = "Average knockouts for season";
        break;
    case KNOCKOUTS_AVERAGE_FOR_SEASON_FOR_USER:
        $title = "Average knockouts for season for player";
        break;
    case KNOCKOUTS_AVERAGE_FOR_USER:
        $title = "Average knockouts for player";
        break;
    case KNOCKOUTS_TOTAL_FOR_SEASON:
        $title = "Total knockouts for season";
        break;
    case KNOCKOUTS_TOTAL_FOR_SEASON_FOR_USER:
        $title = "Total knockouts for season for player";
        break;
    case KNOCKOUTS_TOTAL_FOR_USER:
        $title = "Total knockouts for player";
        break;
    case WINNERS_FOR_SEASON:
        $title = "Winners for season";
        break;
    case WINS_FOR_USER:
        $title = "Wins for Player";
        break;
    case WINS_TOTAL_FOR_SEASON_FOR_USER:
        $title = "Total Wins for season for player";
        break;
    case WINS_AVERAGE_FOR_SEASON_FOR_USER:
        $title = "Average Wins for season for player";
        break;
    case NEMESIS_FOR_USER:
        $title = "Nemesis for player";
        break;
    case BULLY_FOR_USER:
        $title = "Bully for player";
        break;
    case TOURNAMENTS_WON_FOR_USER:
        $title = "Tournaments won";
        break;
    case FINISHES_FOR_USER:
        $title = "Finishes";
        break;
    case TOURNAMENTS_PLAYED_FOR_USER:
        $title = "Tournaments played";
        break;
    case TOURNAMENTS_PLAYED_BY_TYPE_FOR_USER:
        $title = "Tournaments played by type";
        break;
    case TOURNAMENTS_PLAYED_FIRST_FOR_USER:
        $title = "Tournaments played first";
        break;
    default:
        $output .= "No value provided for report id";
}
if (!isset($reportId) || "" == $reportId) {
    $output .= "Unable to identify report to view";
} else {
    $startDate = SessionUtility::getValue(SessionUtility::OBJECT_NAME_START_DATE);
    $endDate = SessionUtility::getValue(SessionUtility::OBJECT_NAME_END_DATE);
    $width = "100%";
    $colFormats = NULL;
    $hideColIndexes = NULL;
    switch ($reportId) {
        case PRIZE_POOL_FOR_SEASON:
            $params = array($startDate, $endDate);
            $resultList = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getPrizePool(startDate: $startDate, endDate: $endDate);
            $titleText = "Prize Pool";
            break;
        case POINTS_TOTAL_FOR_SEASON:
            $orderBy = array(1);
            $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getPoints(playerId: NULL, startDate: $startDate, endDate: $endDate, orderBy: $orderBy, rank: false, limitCount: 5, indexed: true);
            $resultHeaders = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getPoints(playerId: NULL, startDate: $startDate, endDate: $endDate, orderBy: $orderBy, rank: false, limitCount: 5, indexed: false);
            $colFormats = array(array(2, "number", 0));
            $hideColIndexes = array(0, 3, 4, 5);
            $titleText = "Top 5 Points";
            break;
        case POINTS_AVERAGE_FOR_SEASON:
            $orderBy = array(2);
            $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getPoints(playerId: NULL, startDate: $startDate, endDate: $endDate, orderBy: $orderBy, rank: false, limitCount: 5, indexed: true);
            $resultHeaders = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getPoints(playerId: NULL, startDate: $startDate, endDate: $endDate, orderBy: $orderBy, rank: false, limitCount: 5, indexed: false);
            $colFormats = array(array(3, "number", 2));
            $hideColIndexes = array(0, 2, 4, 5);
            $titleText = "Top 5 Avg Points";
            break;
        case EARNINGS_TOTAL_FOR_SEASON:
            $orderBy = array(1);
            $result = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getEarnings(playerId: NULL, startDate: $startDate, endDate: $endDate, year: NULL, championship: false, season: true, totalAndAverage: false, rank: false, orderBy: $orderBy, limitCount: 5, indexed: true);
            $resultHeaders = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getEarnings(playerId: NULL, startDate: $startDate, endDate: $endDate, year: NULL, championship: false, season: true, totalAndAverage: false, rank: false, orderBy: $orderBy, limitCount: 5, indexed: false);
            $colFormats = array(array(2, "currency", 0), array(3, "currency", 0));
            $hideColIndexes = array(0, 3, 4, 5);
            $titleText = "Top 5 Money";
            break;
        case EARNINGS_AVERAGE_FOR_SEASON:
            $orderBy = array(2);
            $result = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getEarnings(playerId: NULL, startDate: $startDate, endDate: $endDate, year: NULL, championship: false, season: true, totalAndAverage: false, rank: false, orderBy: $orderBy, limitCount: 5, indexed: true);
            $resultHeaders = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getEarnings(playerId: NULL, startDate: $startDate, endDate: $endDate, year: NULL, championship: false, season: true, totalAndAverage: false, rank: false, orderBy: $orderBy, limitCount: 5, indexed: false);
            $colFormats = array(array(2, "currency", 0), array(3, "currency", 0));
            $hideColIndexes = array(0, 2, 4, 5);
            $titleText = "Top 5 Avg Money";
            break;
        case KNOCKOUTS_TOTAL_FOR_SEASON:
            $orderBy = array(1);
            $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getKo(playerId: NULL, startDate: $startDate, endDate: $endDate, orderBy: array(1), rank: false, limitCount: 5, indexed: true);
            $resultHeaders = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getKo(playerId: NULL, startDate: $startDate, endDate: $endDate, orderBy: array(1), rank: false, limitCount: 5, indexed: false);
            $colFormats = array(array(2, "number", 0), array(3, "number", 2), array(4, "number", 0));
            $hideColIndexes = array(0, 3, 4, 5);
            $titleText = "Top 5 KO";
            break;
        case KNOCKOUTS_AVERAGE_FOR_SEASON:
            $orderBy = array(2);
            $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getKo(playerId: NULL, startDate: $startDate, endDate: $endDate, orderBy: array(1), rank: false, limitCount: 5, indexed: true);
            $resultHeaders = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getKo(playerId: NULL, startDate: $startDate, endDate: $endDate, orderBy: array(1), rank: false, limitCount: 5, indexed: false);
            $colFormats = array(array(2, "number", 0), array(3, "number", 2), array(4, "number", 0));
            $hideColIndexes = array(0, 2, 4, 5);
            $titleText = "Top 5 Avg KO";
            break;
        case WINNERS_FOR_SEASON:
            $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getWins(startDate: $startDate, endDate: $endDate, playerId: NULL, winsForPlayer: false, winsForSeason: true, rank: false, orderBy: array(), indexed: true);
            $resultHeaders = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getWins(startDate: $startDate, endDate: $endDate, playerId: NULL, winsForPlayer: false, winsForSeason: true, rank: false, orderBy: array(), indexed: false);
            $colFormats = array(array(2, "number", 0));
            $hideColIndexes = array(0, 3, 4, 5);
            $titleText = "Season Winners";
            break;
        case POINTS_TOTAL_FOR_SEASON_FOR_USER:
        case POINTS_AVERAGE_FOR_SEASON_FOR_USER:
            $params = array($startDate, $endDate, $playerId);
            if (POINTS_TOTAL_FOR_SEASON_FOR_USER == $reportId) {
                $orderBy = array(1);
                $valueIndex = 3;
                $formatPlaces = 0;
            } else {
                $orderBy = array(2);
                $valueIndex = 4;
                $formatPlaces = 2;
            }
            $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getPoints(playerId: $playerId, startDate: $startDate, endDate: $endDate, orderBy: $orderBy, rank: true, limitCount: NULL, indexed: true);
            $resultHeaders = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getPoints(playerId: $playerId, startDate: $startDate, endDate: $endDate, orderBy: $orderBy, rank: true, limitCount: NULL, indexed: false);
            $value = array(array("number", "center"), array(4, "number", $formatPlaces), "Points", $valueIndex);
            $rank = array(array("center"), NULL, "Rank", 0);
            break;
        case KNOCKOUTS_TOTAL_FOR_SEASON_FOR_USER:
        case KNOCKOUTS_AVERAGE_FOR_SEASON_FOR_USER:
            $params = array($startDate, $endDate, $playerId);
            if (KNOCKOUTS_TOTAL_FOR_SEASON_FOR_USER == $reportId) {
                $orderBy = array(1);
                $valueIndex = 3;
                $formatPlaces = 0;
            } else {
                $orderBy = array(2);
                $valueIndex = 4;
                $formatPlaces = 2;
            }
            $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getKo(playerId: $playerId, startDate: $startDate, endDate: $endDate, orderBy: $orderBy, rank: true, limitCount: NULL, indexed: true);
            $resultHeaders = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getKo(playerId: $playerId, startDate: $startDate, endDate: $endDate, orderBy: $orderBy, rank: true, limitCount: NULL, indexed: false);
            $value = array(array("number", "center"), array(3, "number", $formatPlaces), "Knockouts", $valueIndex);
            $rank = array(array("center"), NULL, "Rank", 0);
            break;
        case EARNINGS_TOTAL_FOR_SEASON_FOR_USER:
        case EARNINGS_AVERAGE_FOR_SEASON_FOR_USER:
            if (EARNINGS_TOTAL_FOR_SEASON_FOR_USER == $reportId) {
                $orderBy = array(1);
                $valueIndex = 3;
                $formatPlaces = 0;
            } else {
                $orderBy = array(2);
                $valueIndex = 4;
                $formatPlaces = 2;
            }
            $result = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getEarnings(playerId: $playerId, startDate: $startDate, endDate: $endDate, year: NULL, championship: false, season: true, totalAndAverage: true, rank: true, orderBy: $orderBy, limitCount: NULL, indexed: true);
            $resultHeaders = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getEarnings(playerId: $playerId, startDate: $startDate, endDate: $endDate, year: NULL, championship: false, season: true, totalAndAverage: true, rank: true, orderBy: $orderBy, limitCount: NULL, indexed: false);
            $hideColIndexes = array(0, 1, 2, 3, 5);
            $value = array(array("currency", "center"), array(4, "currency", $formatPlaces), "Earnings", $valueIndex);
            $rank = array(array("center"), NULL, "Rank", 0);
            $hideColIndexes = array(0, 2);
            break;
        case WINS_TOTAL_FOR_SEASON_FOR_USER:
        case WINS_AVERAGE_FOR_SEASON_FOR_USER:
            $params = array($startDate, $endDate, $playerId);
            if (WINS_TOTAL_FOR_SEASON_FOR_USER == $reportId) {
                $orderBy = array(1);
                $valueIndex = 3;
                $formatPlaces = 0;
            } else {
                $orderBy = array(2);
                $valueIndex = 4;
                $formatPlaces = 2;
            }
            $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getWins(startDate: $startDate, endDate: $endDate, playerId: $playerId, winsForPlayer: false, winsForSeason: false, rank: true, orderBy: $orderBy, indexed: true);
            $resultHeaders = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getWins(startDate: $startDate, endDate: $endDate, playerId: $playerId, winsForPlayer: false, winsForSeason: false, rank: true, orderBy: $orderBy, indexed: false);
            $hideColIndexes = array(0, 1, 2, 3, 5);
            $value = array(array("number", "center"), array(4, "number", $formatPlaces), "Wins", $valueIndex);
            $rank = array(array("center"), NULL, "Rank", 0);
            break;
        case POINTS_AVERAGE_FOR_USER:
        case POINTS_TOTAL_FOR_USER:
            if (POINTS_TOTAL_FOR_USER == $reportId) {
                $orderBy = array(1);
                $valueIndex = 3;
                $formatPlaces = 0;
            } else {
                $orderBy = array(2);
                $valueIndex = 4;
                $formatPlaces = 2;
            }
            $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getPoints(playerId: $playerId, startDate: NULL, endDate: NULL, orderBy: $orderBy, rank: true, limitCount: NULL, indexed: true);
            $resultHeaders = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getPoints(playerId: $playerId, startDate: NULL, endDate: NULL, orderBy: $orderBy, rank: true, limitCount: NULL, indexed: false);
            $value = array(array("number", "center"), array(3, "number", $formatPlaces), "Points", $valueIndex);
            $rank = array(array("center"), NULL, "Rank", 0);
            break;
        case KNOCKOUTS_AVERAGE_FOR_USER:
        case KNOCKOUTS_TOTAL_FOR_USER:
            if (KNOCKOUTS_TOTAL_FOR_USER == $reportId) {
                $orderBy = array(1);
                $valueIndex = 3;
                $formatPlaces = 0;
            } else {
                $orderBy = array(2);
                $valueIndex = 4;
                $formatPlaces = 2;
            }
            $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getKo(playerId: $playerId, startDate: NULL, endDate: NULL, orderBy: $orderBy, rank: true, limitCount: NULL, indexed: true);
            $resultHeaders = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getKo(playerId: $playerId, startDate: NULL, endDate: NULL, orderBy: $orderBy, rank: true, limitCount: NULL, indexed: false);
            $value = array(array("number", "center"), array(3, "number", $formatPlaces), "Knockouts", $valueIndex);
            $rank = array(array("center"), NULL, "Rank", 0);
            break;
        case EARNINGS_AVERAGE_FOR_USER:
        case EARNINGS_TOTAL_FOR_USER:
            if (EARNINGS_TOTAL_FOR_USER == $reportId) {
                $orderBy = array(1);
                $valueIndex = 3;
                $formatPlaces = 0;
            } else {
                $orderBy = array(2);
                $valueIndex = 4;
                $formatPlaces = 2;
            }
            $result = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getEarnings(playerId: $playerId, startDate: NULL, endDate: NULL, year: NULL, championship: false, season: false, totalAndAverage: true, rank: true, orderBy: $orderBy, limitCount: NULL, indexed: true);
            $resultHeaders = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getEarnings(playerId: $playerId, startDate: NULL, endDate: NULL, year: NULL, championship: false, season: false, totalAndAverage: true, rank: true, orderBy: $orderBy, limitCount: NULL, indexed: false);
            $value = array(array("currency", "center"), array(3, "currency", $formatPlaces), "Earnings", $valueIndex);
            $rank = array(array("center"), NULL, "Rank", 0);
            break;
        case WINS_FOR_USER:
            $orderBy = array(1);
            $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getWins(startDate: $startDate, endDate: $endDate, playerId: $playerId, winsForPlayer: true, winsForSeason: false, rank: true, orderBy: $orderBy, indexed: true);
            $resultHeaders = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getWins(startDate: $startDate, endDate: $endDate, playerId: $playerId, winsForPlayer: true, winsForSeason: false, rank: true, orderBy: $orderBy, indexed: false);
            $value = array(array("number", "center"), array(3, "number", 0), "Wins", 3);
            $rank = array(array("center"), NULL, "Rank", 0);
            break;
        case NEMESIS_FOR_USER:
            $result = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getNemesises(playerId: $playerId, limitCount: NULL, indexed: true);
            $resultHeaders = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getNemesises(playerId: $playerId, limitCount: NULL, indexed: false);
            $colFormats = array(array(1, "number", 0));
            $hideColIndexes = array(1);
            $rank = array(array("center"), NULL, "Rank", 0);
            break;
        case BULLY_FOR_USER:
            $result = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getBullies(knockedOutBy: $playerId, limitCount: NULL, indexed: true);
            $resultHeaders = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getBullies(knockedOutBy: $playerId, limitCount: NULL, indexed: false);
            $colFormats = array(array(1, "number", 0));
            $hideColIndexes = array(1);
            $rank = array(array("center"), NULL, "Rank", 0);
            break;
        case FINISHES_FOR_USER:
            $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getFinishesForDates(playerId: $playerId, startDate: NULL, endDate: NULL, indexed: true);
            $resultHeaders = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getFinishesForDates(playerId: $playerId, startDate: NULL, endDate: NULL, indexed: false);
            $colFormats = array(array(2, "percentage", 2));
            $titleText = "Place of Finish";
            break;
        case TOURNAMENTS_PLAYED_FOR_USER:
            $result = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getPlayed(indexed: true);
            $resultHeaders = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getPlayed(indexed: false);
            $value = array(array("number", "center"), array(4, "number", 0), "Tourneys", 3);
            $rank = array(array("center"), NULL, "Rank", 0);
            break;
        case TOURNAMENTS_PLAYED_BY_TYPE_FOR_USER:
            $result = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getPlayedByType(playerId: $playerId, indexed: true);
            $resultHeaders = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getPlayedByType(playerId: $playerId, indexed: false);
            $colFormats = array(array(4, "number", 0));
            $hideColIndexes = array(0, 2);
            // $width = Constant::FLAG_LOCAL() ? "30%" : "100%";
            $titleText = "Played by type by player";
            break;
    }
    if (PRIZE_POOL_FOR_SEASON != $reportId) {
        array_push($classNames, "top5");
        $headerRow = true;
    }
    if (PRIZE_POOL_FOR_SEASON == $reportId) {
        $output .= "<div class=\"center title\" id=\"title" . ucfirst($reportId) . "\">" . $titleText . "</div>\n";
        $output .= "<div class=\"center number positive\">" . Constant::SYMBOL_CURRENCY_DEFAULT . number_format((float) $resultList[0]["total"], 0) . "</div>\n";
    } else {
        $mode = "";
        $caption = "";
        $hiddenId = NULL;
        $selectedColumnVals = NULL;
        $delimiter = Constant::DELIMITER_DEFAULT;
        $foreignKeys = NULL;
        $html = NULL;
        $showNote = false;
        $hiddenAdditional = NULL;
        $colSpan = NULL;
        $tableIdSuffix = NULL;
        $rankClasses = "";
        switch ($reportId) {
            case POINTS_TOTAL_FOR_SEASON_FOR_USER:
            case POINTS_AVERAGE_FOR_SEASON_FOR_USER:
            case EARNINGS_TOTAL_FOR_SEASON_FOR_USER:
            case EARNINGS_AVERAGE_FOR_SEASON_FOR_USER:
            case KNOCKOUTS_TOTAL_FOR_SEASON_FOR_USER:
            case KNOCKOUTS_AVERAGE_FOR_SEASON_FOR_USER:
            case WINS_TOTAL_FOR_SEASON_FOR_USER:
            case WINS_AVERAGE_FOR_SEASON_FOR_USER:
            case POINTS_TOTAL_FOR_USER:
            case POINTS_AVERAGE_FOR_USER:
            case EARNINGS_TOTAL_FOR_USER:
            case EARNINGS_AVERAGE_FOR_USER:
            case KNOCKOUTS_TOTAL_FOR_USER:
            case KNOCKOUTS_AVERAGE_FOR_USER:
            case WINS_FOR_USER:
            case TOURNAMENTS_PLAYED_FOR_USER:
                if (POINTS_TOTAL_FOR_SEASON_FOR_USER == $reportId || POINTS_AVERAGE_FOR_SEASON_FOR_USER == $reportId) {
                    $colFormats = array(array(1, "number", 0), array(4, "number", 0), array(5, "number", 2));
                    $hideColIndexes = array(0, 2, 5);
                    if (POINTS_TOTAL_FOR_SEASON_FOR_USER == $reportId) {
                        $titleText = "Season Points";
                    } else if (POINTS_AVERAGE_FOR_SEASON_FOR_USER == $reportId) {
                        $titleText = "Season Avg Points";
                    }
                    $dialogParameters = array($titleText);
                } else if (KNOCKOUTS_TOTAL_FOR_SEASON_FOR_USER == $reportId || KNOCKOUTS_AVERAGE_FOR_SEASON_FOR_USER == $reportId) {
                    $colFormats = array(array(1, "number", 0), array(4, "number", 0), array(5, "number", 2));
                    $hideColIndexes = array(0, 2, 5);
                    if (KNOCKOUTS_TOTAL_FOR_SEASON_FOR_USER == $reportId) {
                        $titleText = "Season Knockouts";
                    } else if (KNOCKOUTS_AVERAGE_FOR_SEASON_FOR_USER == $reportId) {
                        $titleText = "Season Avg Knockouts";
                    }
                    $dialogParameters = array($titleText);
                } else if (EARNINGS_TOTAL_FOR_SEASON_FOR_USER == $reportId || EARNINGS_AVERAGE_FOR_SEASON_FOR_USER == $reportId) {
                    $colFormats = array(array(1, "number", 0), array(4, "currency", 0), array(5, "currency", 2));
                    $hideColIndexes = array(0, 2, 5);
                    if (EARNINGS_TOTAL_FOR_SEASON_FOR_USER == $reportId) {
                        $titleText = "Season Earnings";
                    } else if (EARNINGS_AVERAGE_FOR_SEASON_FOR_USER == $reportId) {
                        $titleText = "Season Avg Earnings";
                    }
                    $dialogParameters = array($titleText);
                } else if (WINS_TOTAL_FOR_SEASON_FOR_USER == $reportId || WINS_AVERAGE_FOR_SEASON_FOR_USER == $reportId) {
                    $colFormats = array(array(1, "number", 0), array(4, "number", 0), array(5, "percentage", 2));
                    $hideColIndexes = array(0, 2, 5);
                    if (WINS_TOTAL_FOR_SEASON_FOR_USER == $reportId) {
                        $titleText = "Season Wins";
                    } else if (WINS_AVERAGE_FOR_SEASON_FOR_USER == $reportId) {
                        $titleText = "Season Avg Wins";
                    }
                    $dialogParameters = array($titleText);
                } else if (POINTS_TOTAL_FOR_USER == $reportId || POINTS_AVERAGE_FOR_USER == $reportId) {
                    $colFormats = array(array(1, "number", 0), array(4, "number", 0), array(5, "number", 2));
                    if (POINTS_TOTAL_FOR_USER == $reportId) {
                        $titleText = "Lifetime Points";
                        $hideColIndexes = array(0, 2, 5);
                    } else if (POINTS_AVERAGE_FOR_USER == $reportId) {
                        $titleText = "Lifetime Avg Points";
                        $hideColIndexes = array(0, 2, 4);
                    }
                    $dialogParameters = array($titleText);
                } else if (EARNINGS_TOTAL_FOR_USER == $reportId || EARNINGS_AVERAGE_FOR_USER == $reportId) {
                    $colFormats = array(array(1, "number", 0), array(4, "currency", 0), array(5, "currency", 2));
                    if (EARNINGS_TOTAL_FOR_USER == $reportId) {
                        $titleText = "Lifetime Earnings";
                        $hideColIndexes = array(0, 2, 5);
                    } else if (EARNINGS_AVERAGE_FOR_USER == $reportId) {
                        $titleText = "Lifetime Avg Earnings";
                        $hideColIndexes = array(0, 2, 4);
                    }
                    $dialogParameters = array($titleText);
                } else if (KNOCKOUTS_TOTAL_FOR_USER == $reportId || KNOCKOUTS_AVERAGE_FOR_USER == $reportId) {
                    $colFormats = array(array(1, "number", 0), array(4, "number", 0), array(5, "number", 2));
                    if (KNOCKOUTS_TOTAL_FOR_USER == $reportId) {
                        $titleText = "Lifetime KO";
                        $hideColIndexes = array(0, 2, 5);
                    } else if (KNOCKOUTS_AVERAGE_FOR_USER == $reportId) {
                        $titleText = "Lifetime Avg KO";
                        $hideColIndexes = array(0, 2, 4);
                    }
                    $dialogParameters = array($titleText);
                } else if (WINS_FOR_USER == $reportId) {
                    $colFormats = array(array(1, "number", 0), array(5, "percentage", 2));
                    $hideColIndexes = array(0, 2, 5);
                    $dialogParameters = array("Lifetime Wins");
                    $titleText = "Lifetime Wins";
                } else if (TOURNAMENTS_PLAYED_FOR_USER == $reportId) {
                    $colFormats = array(array(1, "number", 0), array(4, "number", 0));
                    $hideColIndexes = array(0, 2);
                    $dialogParameters = array("Lifetime Tourneys");
                    $titleText = "Lifetime Tourneys";
                }
                $output .= "<div class=\"center title\" id=\"title" . ucfirst($reportId) . "\">" . $titleText . "</div>\n";
                $suffix = str_replace(" ", "", $dialogParameters[0]);
                // if format provided then adjust indexes to add 1 for rownum used for ranking
                if (isset($value[1])) {
                    $value[1][0] += 1;
                }
                // adjust index to add 1 for rownum used for ranking
                $value[3] += 1;
                $rank[3] += 1;
                $htmlTable = new HtmlTable(caption: NULL, class: NULL, colspan: NULL, columnFormat: $colFormats, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), delimiter: $delimiter, foreignKeys: NULL, header: true, hiddenAdditional: NULL, hiddenId: NULL, hideColumnIndexes: $hideColIndexes, html: NULL, id: NULL, link: NULL, note: true, selectedRow: NULL, suffix: "Rank" . $suffix, width: "100%");
                $outputTemp = $htmlTable->getHtml(results: $result, resultHeaders: $resultHeaders);
                if (0 < count($result)) {
                    foreach($result as $row) {
                        if ($row[2] == $playerId) {
                            $rowPlayer = $row;
                            break;
                        }
                    }
                    $valueClasses = HtmlUtility::buildClasses(aryClasses: $value[0], value: $rowPlayer[$value[3]]);
                    $rowPlayer[$value[3]] = HtmlUtility::formatData(format: $value[1], value: $rowPlayer[$value[3]]);
                    $output .= "<div " . (($valueClasses != "") ? "class=\"" . $valueClasses . "\"" : "") . " id=\"value\">" . $rowPlayer[$value[3]] . "</div>\n";
                    $rankClasses = HtmlUtility::buildClasses(aryClasses: $rank[0], value: $rowPlayer[$rank[3]]);
                    $output .=
                        "<script type=\"module\">\n" .
                        "  import { inputLocal } from \"./scripts/top5.js\";\n" .
                        "  document.querySelector(\"#rank_" . str_replace(" ", "", $titleText) . "_link\").addEventListener(\"click\", (evt) => inputLocal.showFullList({title: '" . $dialogParameters[0] . "', userFullName: '" . SessionUtility::getValue(SessionUtility::OBJECT_NAME_NAME) . "'}));\n" .
                        "</script>\n";
                    $output .= "<div " . (($rankClasses != "") ? "class=\"" . $rankClasses . "\"" : "") . " id=\"rank_" . $playerId . "\"><a href=\"#\" id=\"rank_" . str_replace(" ", "", $titleText) . "_link\">" . $rank[2] . ": " . $rowPlayer[$rank[3]] . "</a></div>\n";
                    $output .=
                        "<dialog class=\"dialog\" id=\"dialogRankAll" . str_replace(" ", "", $titleText) . "\">\n" .
                        " <form method=\"dialog\">\n" .
                        "  <header>\n" .
                        "   <h2>" . $titleText . "</h2>\n" .
                        "   <button class=\"dialogButton\" id=\"dialogRankAll" . str_replace(" ", "", $titleText) . "-header--cancel-btn\">X</button>\n" .
                        "  </header>\n" .
                        "  <main>\n" .
                    $outputTemp .
                        "  </main>\n" .
                        " </form>\n" .
                        "</dialog>\n";
                }
                if (isset($navigation)) {
                    $output .= "</div>\n";
                }
                break;
            case TOURNAMENTS_WON_FOR_USER:
                $titleText = "Tournaments Won";
                $output .= "<div class=\"center title\" id=\"title" . ucfirst($reportId) . "\">" . $titleText . "</div>\n";
                $resultList = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getWon(playerId: $playerId);
                if (0 < count($resultList)) {
                    $output .=
                        "<script type=\"module\">\n" .
                        "  $(document).ready(function() {\$(\"#title" . ucfirst($reportId) . "\").text($(\"#title" . ucfirst($reportId) . "\").text() + ' (' + " . count($resultList) . " + ')');});\n" .
                        "</script>\n";
                    $ctr = 0;
                    foreach ($resultList as $row) {
                        $limitType = new LimitType(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["limit_type_id"], name: "");
                        $gameType = new GameType(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["game_type_id"], name: "");
                        $specialType = new SpecialType(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["special_type_id"], description: NULL, multiplier: 0);
                        $player = new Player(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["player_id"], name: "", username: NULL, password: NULL, email: NULL, phone: NULL, administrator: "0", registrationDate: new DateTime(), approvalDate: NULL, approvalUserid: NULL, approvalName: NULL, rejectionDate: NULL, rejectionUserid: NULL, rejectionName: NULL, active: "1", resetSelector: NULL, resetToken: NULL, resetExpires: NULL, rememberSelector: NULL, rememberToken: NULL, rememberExpires: NULL);
                        $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getById(playerId: $row["player_id"]);
                        $player->createFromEntity(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), players: $result[0]);
                        $location = new Location(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["location_id"], name: $row["location"], address: $row["location_address"], city: $row["location_city"], state: $row["location_state"], zipCode: $row["location_zip_code"], player: $player, count: 0, active: "1", map: NULL, mapName: NULL, tournamentCount: 0);
                        $result = $entityManager->getRepository(Constant::ENTITY_LOCATIONS)->getById(locationId: $row["location_id"]);
                        $location->createFromEntity(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), locations: $result[0]);
                        $group = new Group(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: 0, name: "");
                        $result = $entityManager->getRepository(Constant::ENTITY_GROUPS)->getById(groupId: $row["group_id"]);
                        $group->createFromEntity(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), groups: $result[0]);
                        $structure = new Structure(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: 0, place: 0, percentage: 0);
                        $result = $entityManager->getRepository(Constant::ENTITY_STRUCTURES)->getById(payoutId: $group->getGroups()->getGroupPayouts()[0]->getPayouts()->getPayoutId());
                        $structure->createFromEntity(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), structures: $result[0]);
                        $payout = new Payout(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: 0, name: "", minPlayers: 0, maxPlayers: 0, structures: array($structure));
                        $result = $entityManager->getRepository(Constant::ENTITY_PAYOUTS)->getById(payoutId: $group->getGroups()->getGroupPayouts()[0]->getPayouts()->getPayoutId());
                        $payout->createFromEntity(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), payouts: $result[0]);
                        $groupPayout = new GroupPayout(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: "", group: $group, payouts: array($payout));
                        $tournament = new Tournament(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["id"], description: $row["description"], comment: $row["comment"], limitType: $limitType, gameType: $gameType, specialType: $specialType, chipCount: $row["chips"], location: $location, date: new DateTime(datetime: $row["date"]), startTime: new DateTime(datetime: $row["start"]), buyinAmount: $row["buyin"], maxPlayers: $row["max players"], maxRebuys: $row["max"], rebuyAmount: $row["amt"], addonAmount: $row["amt "], addonChipCount: $row["chips "], groupPayout: $groupPayout, rake: (float) ($row["rake"] * 100), registeredCount: 0, buyinsPaid: 0, rebuysPaid: 0, rebuysCount: 0, addonsPaid: 0, enteredCount: $row["enteredCount"], earnings: (int) $row["earnings"]);
                        $ctr++;
                        $tournamentInfo = DateTimeUtility::formatDisplayDate(value: $tournament->getDate()) . ", " . DateTimeUtility::formatDisplayTime(value: $tournament->getStartTime()) . " " . $tournament->getLimitType()->getName() . " " . $tournament->getGameType()->getName() . " " . " " . $tournament->getMaxRebuys() . "r " . (0 < $tournament->getAddonAmount() ? "+a" : "") . " " . $tournament->getChipCount() . " chips " . $tournament->getEnteredCount() . " played $" . $tournament->getEarnings() . " won";
                        $output .= "<div style=\"display: inline-block; vertical-align: top; width: 3%;\">" . $ctr . "</div>\n<div class=\"fixedWidth\"  style=\"display: inline-block; text-align: left; width: 95%;\">" . $tournamentInfo . "</div>\n";
                    }
                } else {
                    $output .= "<div class=\"center\">No data found</div>\n";
                }
                break;
            case TOURNAMENTS_PLAYED_FIRST_FOR_USER:
                $titleText = "";
                $output .= "<div class=\"center title\" id=\"title" . ucfirst($reportId) . "\">" . $titleText . "</div>\n";
                $resultList = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getResultsMinDate(playerId: $playerId);
                if (0 < count($resultList)) {
                    $date = new DateTime(datetime: $resultList[0]["tournamentDate"]);
                    $output .= "<div class=\"center\">Member since " . DateTimeUtility::formatDisplayDate(value: $date) . "</div>\n";
                }
                break;
            default:
                if (NEMESIS_FOR_USER == $reportId || BULLY_FOR_USER == $reportId) {
                    if (NEMESIS_FOR_USER == $reportId) {
                        $tableIdSuffix = "Nemesis";
                        $titleText = "Your Nemesis";
                    } else if (BULLY_FOR_USER == $reportId) {
                        $tableIdSuffix = "Bully";
                        $titleText = "Your Victims";
                    }
                    array_push($classNames, $tableIdSuffix);
                } else {
                    $tableIdSuffix = ucfirst($reportId);
                }
                $output .= "<div class=\"center title\" id=\"title" . $tableIdSuffix . "\">" . $titleText . "</div>\n";
                $htmlTable = new HtmlTable(caption: $caption, class: $classNames, colspan: $colSpan, columnFormat: $colFormats, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), delimiter: $delimiter, foreignKeys: $foreignKeys, header: $headerRow, hiddenAdditional: $hiddenAdditional, hiddenId: $hiddenId, hideColumnIndexes: $hideColIndexes, html: $html, id: NULL, link: NULL, note: $showNote, selectedRow: $selectedColumnVals, suffix: $tableIdSuffix, width: $width);
                $output .= $htmlTable->getHtml(results: $result, resultHeaders: $resultHeaders);
                $htmlTable2 = new HtmlTable(caption: $caption, class: $classNames, colspan: $colSpan, columnFormat: $colFormats, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), delimiter: $delimiter, foreignKeys: $foreignKeys, header: $headerRow, hiddenAdditional: $hiddenAdditional, hiddenId: $hiddenId, hideColumnIndexes: $hideColIndexes, html: $html, id: NULL, link: NULL, note: $showNote, selectedRow: $selectedColumnVals, suffix: "All" . $tableIdSuffix, width: $width);
                $outputTemp = $htmlTable2->getHtml(results: $result, resultHeaders: $resultHeaders);
                if (NEMESIS_FOR_USER == $reportId || BULLY_FOR_USER == $reportId) {
                    if (NEMESIS_FOR_USER == $reportId) {
                        $title = "Nemesises";
                        $tableIdSuffix = "Nemesis";
                        $titleText = "Nemesis";
                    } else if (BULLY_FOR_USER == $reportId) {
                        $title = "Bullies";
                        $tableIdSuffix = "Bullies";
                        $titleText = "Bullies";
                    }
                    $rankClasses = HtmlUtility::buildClasses(aryClasses: $rank[0], value: "");
                    $output .=
                        "<script type=\"module\">\n" .
                        "  import { inputLocal } from \"./scripts/top5.js\";\n" .
                        "  document.querySelector(\"#rank_" . $tableIdSuffix . "_link\").addEventListener(\"click\", (evt) => inputLocal.showFullList" . $tableIdSuffix . "());\n" .
                        "</script>\n";
                    $output .= "<div " . (($rankClasses != "") ? "class=\"" . $rankClasses . "\"" : "") . " id=\"rank_" . $tableIdSuffix . "\"><a href=\"#\" id=\"rank_" . $tableIdSuffix . "_link\">See full list</a></div>\n";
                    $output .=
                        "<dialog class=\"dialog\" id=\"dialogRankAll" . str_replace(" ", "", $titleText) . "\">\n" .
                        " <form method=\"dialog\">\n" .
                        "  <header>\n" .
                        "   <h2>" . $titleText . "</h2>\n" .
                        "   <button class=\"dialogButton\" id=\"dialogRankAll" . str_replace(" ", "", $titleText) . "-header--cancel-btn\">X</button>\n" .
                        "  </header>\n" .
                        "  <main>\n" .
                    $outputTemp .
                        "  </main>\n" .
                        " </form>\n" .
                        "</dialog>\n";
                }
                break;
        }
    }
    $hiddenReportId = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: array(REPORT_ID_PARAM_NAME), cols: NULL, disabled: false, id: REPORT_ID_PARAM_NAME, maxLength: NULL, name: REPORT_ID_PARAM_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $reportId, wrap: NULL);
    $output .= $hiddenReportId->getHtml();
    $hiddenMode = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::FIELD_NAME_MODE, maxLength: NULL, name: Constant::FIELD_NAME_MODE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $mode, wrap: NULL);
    $output .= $hiddenMode->getHtml();
    if (!isset($parentObjectId)) {
        $output .= "</div>\n";
    }
}
if (isset($navigation)) {
    $smarty->assign("content", $output);
    $smarty->display("top5.tpl");
} else {
    return $output;
}