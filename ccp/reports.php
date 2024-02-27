<?php
declare(strict_types = 1);
namespace ccp;
use DateTime;
use Poker\Ccp\Model\Base;
use Poker\Ccp\Model\Constant;
use Poker\Ccp\Model\FormControl;
use Poker\Ccp\Model\FormOption;
use Poker\Ccp\Model\FormSelect;
use Poker\Ccp\Model\HtmlTable;
use Poker\Ccp\Utility\DateTimeUtility;
use Poker\Ccp\Utility\SessionUtility;
require_once "init.php";
define("REPORT_ID_PARAM_NAME", "reportId");
define("PLAYER_ID_PARAM_NAME", "playerId");
define("TOURNAMENT_ID_PARAM_NAME", "tournamentId");
define("SEASON_PARAM_NAME", "season");
define("SEASON_SELECTION_PARAM_NAME", "seasonSelection");
define("CHAMPIONSHIP_PARAM_NAME", "championship");
define("REPORT_ID_TOURNAMENT_RESULTS", "results");
define("REPORT_ID_TOTAL_POINTS", "pointsTotal");
define("REPORT_ID_EARNINGS", "earnings");
define("REPORT_ID_EARNINGS_CHAMPIONSHIP", "earningsChampionship");
define("REPORT_ID_KNOCKOUTS", "knockouts");
define("REPORT_ID_SUMMARY", "summary");
define("REPORT_ID_WINNERS", "winners");
define("REPORT_ID_FINISHES", "finishes");
define("REPORT_ID_CHAMPIONSHIP", "championship");
define("REPORT_ID_FEES", "fees");
define("SORT_ID_PARAM_NAME", "sort");
define("GROUP_PARAM_NAME", "group");
define("SEASON_START_DATE_PARAM_NAME", "seasonStartDate");
define("SEASON_END_DATE_PARAM_NAME", "seasonEndDate");
define("REPORT_ID_FIELD_NAME", "reportId");
define("SEASON_START_DATE_FIELD_NAME", "seasonStartDate");
define("SEASON_END_DATE_FIELD_NAME", "seasonEndDate");
if (!isset($reportId)) {
    $reportId = (isset($_POST[REPORT_ID_PARAM_NAME]) ? $_POST[REPORT_ID_PARAM_NAME] : isset($_GET[REPORT_ID_PARAM_NAME])) ? $_GET[REPORT_ID_PARAM_NAME] : NULL;
}
$output = "<div class=\"contentReport\">\n";
$playerId = (int) ((isset($_POST[PLAYER_ID_PARAM_NAME]) ? $_POST[PLAYER_ID_PARAM_NAME] : isset($_GET[PLAYER_ID_PARAM_NAME])) ? $_GET[PLAYER_ID_PARAM_NAME] : SessionUtility::getValue("userid"));
if (!isset($tournamentId)) {
    $tournamentId = (int) ((isset($_POST[TOURNAMENT_ID_PARAM_NAME]) ? $_POST[TOURNAMENT_ID_PARAM_NAME] : isset($_GET[TOURNAMENT_ID_PARAM_NAME])) ? $_GET[TOURNAMENT_ID_PARAM_NAME] : NULL);
}
if (!isset($seasonSelection)) {
    $seasonSelection = (isset($_POST[SEASON_SELECTION_PARAM_NAME]) ? $_POST[SEASON_SELECTION_PARAM_NAME] : isset($_GET[SEASON_SELECTION_PARAM_NAME])) ? $_GET[SEASON_SELECTION_PARAM_NAME] : "hide";
}
if (!isset($seasonId)) {
    $seasonTemp = (isset($_POST[SEASON_PARAM_NAME]) ? $_POST[SEASON_PARAM_NAME] : isset($_GET[SEASON_PARAM_NAME])) ? $_GET[SEASON_PARAM_NAME] : NULL;
    $arySeason = isset($seasonTemp) ? explode("::", $seasonTemp) : array("");
    $seasonId = $arySeason[0];
    if (count($arySeason) > 1) {
        $seasonStartDate = $arySeason[1];
        $seasonEndDate = $arySeason[2];
        $startDate = $seasonStartDate;
        $endDate = $seasonEndDate;
    } else {
        $seasonStartDate = NULL;
        $seasonEndDate = NULL;
    }
}
$championship = (isset($_POST[CHAMPIONSHIP_PARAM_NAME]) ? true : isset($_GET[CHAMPIONSHIP_PARAM_NAME])) ? true : false;
if ("ALL" == $seasonId) {
    $startDate = NULL;
    $endDate = NULL;
    $year = $seasonId;
} else {
    $startDate = isset($seasonStartDate) ? DateTime::createFromFormat("Y-m-d", $seasonStartDate) : SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_START_DATE);
    $endDate = isset($seasonEndDate) ? DateTime::createFromFormat("Y-m-d", $seasonEndDate) : SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_END_DATE);
    $year = DateTimeUtility::formatYear(value: $startDate);
    $yearEnd = DateTimeUtility::formatYear(value: $endDate);
    if ($year != $yearEnd) {
        $year .= "', '" . $yearEnd;
    }
    if (!isset($seasonId) || empty($seasonId)) {
        $seasonId = SessionUtility::getValue(SessionUtility::OBJECT_NAME_ID);
    }
}
$group = (isset($_POST[GROUP_PARAM_NAME]) ? $_POST[GROUP_PARAM_NAME] : isset($_GET[GROUP_PARAM_NAME])) ? $_GET[GROUP_PARAM_NAME] : NULL;
$style = "";
if ($reportId == REPORT_ID_SUMMARY) {
    $style .= "<style media=\"all\" type=\"text/css\">body {max-width: unset;}</style>\n";
}
$smarty->assign("heading", "");
$smarty->assign("style", $style);
$smarty->assign("formName", "frmReports");
$smarty->assign("action", $_SERVER["SCRIPT_NAME"]);
if (!isset($reportId)) {
    $output .= "Unable to identify report to view";
} else {
    switch ($reportId) {
        case REPORT_ID_TOURNAMENT_RESULTS:
        if (!isset($tournamentId)) {
            $output .= "Unable to identify tournament to view results for";
        }
        break;
    }
    switch ($reportId) {
        case REPORT_ID_TOURNAMENT_RESULTS:
            $title = "Tournament results";
            break;
        case REPORT_ID_TOTAL_POINTS:
            $title = "Total points";
            break;
        case REPORT_ID_EARNINGS:
            $title = "Earnings";
            break;
        case REPORT_ID_EARNINGS_CHAMPIONSHIP:
            $title = "Earnings (Championship)";
            break;
        case REPORT_ID_KNOCKOUTS:
            $title = "Knockouts";
            break;
        case REPORT_ID_SUMMARY:
            $title = "Summary";
            break;
        case REPORT_ID_WINNERS:
            $title = "Winners";
            break;
        case REPORT_ID_FINISHES:
            $title = "Finishes";
            break;
        case REPORT_ID_CHAMPIONSHIP:
            $title = "Championship";
            break;
        case REPORT_ID_FEES:
            $title = "Fees";
            break;
        default:
            $output = "No value provided for report id";
    }
    $smarty->assign("title", "Chip Chair and a Prayer " . $title . " Report");
    $classNames = NULL;
    $caption = NULL;
    $colFormats = NULL;
    $hiddenId = NULL;
    $selectedColumnVals = "";
    $delimiter = Constant::DELIMITER_DEFAULT;
    $foreignKeys = NULL;
    $headerRow = true;
    $html = NULL;
    $showNote = false;
    $hiddenAdditional = NULL;
    $hideColIndexes = NULL;
    $colSpan = NULL;
    $link = NULL;
    switch ($reportId) {
        case REPORT_ID_TOURNAMENT_RESULTS:
            $prizePool = NULL;
            $resultList = $entityManager->getRepository(Constant::ENTITY_SEASONS)->getByTournamentIdAndSpecialTypeDescription(tournamentId: $tournamentId, specialTypeDescription: Constant::DESCRIPTION_CHAMPIONSHIP);
            if (0 < count($resultList)) {
                $resultList2 = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getPrizePool(startDate: new DateTime($resultList[0]["season_start_date"]), endDate: new DateTime($resultList[0]["season_end_date"]));
                $prizePool = (int) $resultList2[0]["total"];
            }
            $resultList = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getAllMultiple(championship: false, tournamentDate: NULL, startTime: NULL, tournamentId: NULL, notEntered: false, ordered: true, mode: NULL, interval: NULL, limitCount: NULL);
            $ctr = 0;
            foreach($resultList as $row) {
                $resultListIds[$ctr] = $row["id"];
                $ctr++;
            }
            $resultList = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getAllMultiple(championship: false, tournamentDate: NULL, startTime: NULL, tournamentId: $tournamentId, notEntered: false, ordered: false, mode: NULL, interval: NULL, limitCount: NULL);
            if (0 < count($resultList)) {
                $arrayIndex = array_keys($resultListIds, $resultList[0]["id"]);
                $width = "100%";
                $output .= "<div class=\"center\" style=\"width: " . $width . ";\">\n";
                if (count($arrayIndex) > 0) {
                    $output .= "<a class=\"link\" title=\"Previous\" href=\"reports.php?reportId=results&amp;tournamentId=" . $resultListIds[$arrayIndex[0] - 1] . "\"><span class=\"linkText\">Previous</span><i class=\"fa fa-caret-left\"></i></a>\n";
                }
                if ($arrayIndex[0] < (count($resultListIds) - 1)) {
                    $output .= "<a class=\"link\" title=\"Next\" href=\"reports.php?reportId=results&amp;tournamentId=" . $resultListIds[$arrayIndex[0] + 1] . "\"><i class=\"fa fa-caret-right\"></i><span class=\"linkText\">Next</span></a>\n";
                }
                $output .= "</div>\n";
                $output .= "<strong>Game details: " . $resultList[0]["description"] . ", " . $resultList[0]["limit"] . " " . $resultList[0]["type"] . " " . $resultList[0]["comment"] . " at " . $resultList[0]["location"] . "</strong>";
            }
            $result = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getForTournament(prizePool: $prizePool, tournamentId: $tournamentId, indexed: true);
            $resultHeaders = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getForTournament(prizePool: $prizePool, tournamentId: $tournamentId, indexed: false);
            $colFormats = array(array(0, "number", 0), array(2, "currency,negative", 0), array(3, "currency,negative", 0), array(4, "currency", 0), array(5, "number", 0));
            $hideColIndexes = array(8);
            break;
        case REPORT_ID_TOTAL_POINTS:
            $result = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getOrderedByPoints(startDate: $startDate, endDate: $endDate, indexed: true);
            $resultHeaders = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getOrderedByPoints(startDate: $startDate, endDate: $endDate, indexed: false);
            $colFormats = array(array(1, "number", 0), array(2, "number", 2), array(3, "number", 0));
            $hideColIndexes = array(4);
            $width = "100%";
            break;
        case REPORT_ID_EARNINGS:
            $result = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getOrderedByEarnings(startDate: $startDate, endDate: $endDate, indexed: true);
            $resultHeaders = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getOrderedByEarnings(startDate: $startDate, endDate: $endDate, indexed: false);
            $colFormats = array(array(1, "currency", 0), array(2, "currency", 2), array(3, "currency", 0), array(4, "number", 0));
            $hideColIndexes = array(5);
            $width = "100%";
            break;
        case REPORT_ID_EARNINGS_CHAMPIONSHIP:
            $result = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getEarnings(playerId: NULL, startDate: NULL, endDate: NULL, year: ("ALL" == $seasonId ? NULL : (int) $year), championship: true, season: false, totalAndAverage: false, rank: false, orderBy: NULL, limitCount: NULL, indexed: true);
            $resultHeaders = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getEarnings(playerId: NULL, startDate: NULL, endDate: NULL, year: ("ALL" == $seasonId ? NULL : (int) $year), championship: true, season: false, totalAndAverage: false, rank: false, orderBy: NULL, limitCount: NULL, indexed: false);
            $colFormats = array(array(2, "currency", 0), array(3, "currency", 0));
            $hideColIndexes = array(0);
            $width = "100%";
            break;
        case REPORT_ID_KNOCKOUTS:
            $result = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getOrderedByKos(startDate: $startDate, endDate: $endDate, indexed: true);
            $resultHeaders = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getOrderedByKos(startDate: $startDate, endDate: $endDate, indexed: false);
            $colFormats = array(array(2, "number", 0), array(3, "number", 2), array(4, "number", 0), array(5, "number", 0));
            $hideColIndexes = array(0, 6);
            $width = "100%";
            break;
        case REPORT_ID_SUMMARY:
            $result = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getOrderedSummary(startDate: $startDate, endDate: $endDate, championship: $championship, stats: false, indexed: true);
            $resultHeaders = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getOrderedSummary(startDate: $startDate, endDate: $endDate, championship: $championship, stats: false, indexed: false);
            $colFormats = array(array(1, "number", 0), array(2, "number", 0), array(3, "number", 0), array(4, "number", 0), array(5, "percentage", 2), array(6, "number", 2), array(7, "number", 0), array(8, "number", 0), array(9, "currency", 0), array(10, "currency", 0), array(11, "currency", 0), array(12, "currency", 0), array(13, "currency", 0), array(14, "currency", 0), array(15, "currency", 0), array(16, "currency", 0));
            $hideColIndexes = array(3, 17);
            $colSpan = array(array("Final Tables", "Finish", "Money Out", "Money In"), array(4, 6, 9, 14), array(array(5), array(7, 8), array(10, 11, 12), array(13, 15, 16)));
            $width = "100%";
            break;
        case REPORT_ID_WINNERS:
            $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getWins(startDate: $startDate, endDate: $endDate, playerId: NULL, winsForPlayer: false, winsForSeason: true, rank: false, orderBy: array(), indexed: true);
            $resultHeaders = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getWins(startDate: $startDate, endDate: $endDate, playerId: NULL, winsForPlayer: false, winsForSeason: true, rank: false, orderBy: array(), indexed: false);
            $colFormats = array(array(2, "number", 0), array(3, "percentage", 2), array(4, "number", 0));
            $hideColIndexes = array(0, 5);
            $width = "100%";
            break;
        case REPORT_ID_FINISHES:
            $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getFinishesForDates(playerId: $playerId, startDate: $startDate, endDate: $endDate, indexed: true);
            $resultHeaders = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getFinishesForDates(playerId: $playerId, startDate: $startDate, endDate: $endDate, indexed: false);
            $colFormats = array(array(0, "number", 0), array(1, "number", 0), array(2, "percentage", 2));
            $caption = "";
            $width = "100%";
            break;
        case REPORT_ID_CHAMPIONSHIP:
            $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getChampionshipEarnings(groupBy: $group ? "player_id" : "yr, player_id", group: isset($group) ? true : false, indexed: true);
            $resultHeaders = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getChampionshipEarnings(groupBy: $group ? "player_id" : "yr, player_id", group: isset($group) ? true : false, indexed: false);
            $colFormats = array(array($group ? 1 : 3, "currency", 0));
            $hideColIndexes = $group ? array(2, 3) : array(1, 4, 5);
            $width = "100%";
            break;
        case REPORT_ID_FEES:
            $result = $entityManager->getRepository(Constant::ENTITY_FEES)->getBySeason(indexed: true);
            $resultHeaders = $entityManager->getRepository(Constant::ENTITY_FEES)->getBySeason(indexed: false);
            $colFormats = array(array(2, "date", 0), array(3, "date", 0), array(4, "currency", 0));
            $hideColIndexes = array(0);
            $width = "100%";
            $link = array(array(4), array("#", array("seasonId"), array(0), 4, "fee_detail_link"));
            break;
    }
    if ("show" == $seasonSelection) {
        $output .= "<div class=\"center\">\n";
        $selectSeason = new FormSelect(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_SEASON, class: NULL, disabled: false, id: Base::build("season", NULL), multiple: false, name: Base::build("season", NULL), onClick: NULL, readOnly: false, size: 1, suffix: NULL, value: NULL);
        $output .= "Season: " . $selectSeason->getHtml();
        $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: "", suffix: NULL, text: "Overall", value: "ALL");
        $output .= $option->getHtml();
        $resultList = $entityManager->getRepository(Constant::ENTITY_SEASONS)->getById(seasonId: NULL);
        if (0 < count($resultList)) {
            $ctr = 0;
            while ($ctr < count($resultList)) {
                $seasonText = $resultList[$ctr]->getSeasonDescription() . " (" . DateTimeUtility::formatDatabaseDate(value: $resultList[$ctr]->getSeasonStartDate()) . " - " . DateTimeUtility::formatDatabaseDate(value: $resultList[$ctr]->getSeasonEndDate()) . ")";
                $seasonValue = $resultList[$ctr]->getSeasonId() . "::" . DateTimeUtility::formatDatabaseDate(value: $resultList[$ctr]->getSeasonStartDate()) . "::" . DateTimeUtility::formatDatabaseDate(value: $resultList[$ctr]->getSeasonEndDate());
                $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: $startDate == NULL || $endDate == NULL ? "" : $seasonId . "::" . DateTimeUtility::formatDatabaseDate(value: $startDate) . "::" . DateTimeUtility::formatDatabaseDate(value: $endDate), suffix: NULL, text: $seasonText, value: $seasonValue);
                $output .= $option->getHtml();
                $ctr++;
            }
        }
        $output .= "</select>\n";
        $hiddenSeasonStartDate = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: SEASON_START_DATE_FIELD_NAME, maxLength: NULL, name: SEASON_START_DATE_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $seasonStartDate, wrap: NULL);
        $output .= $hiddenSeasonStartDate->getHtml();
        $hiddenSeasonEndDate = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: SEASON_END_DATE_FIELD_NAME, maxLength: NULL, name: SEASON_END_DATE_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $seasonEndDate, wrap: NULL);
        $output .= $hiddenSeasonEndDate->getHtml();
        if (REPORT_ID_SUMMARY == $reportId) {
            $output .= "&nbsp;&nbsp;Include Championships: \n";
            $checkboxChampionship = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: $championship, class: NULL, cols: NULL, disabled: false, id: "championship", maxLength: NULL, name: "championship", import: NULL, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_CHECKBOX, value: Constant::FLAG_YES, wrap: NULL);
            $output .= $checkboxChampionship->getHtml();
        }
        $output .= "</div>\n";
    }
    $htmlTable = new HtmlTable(caption: $caption, class: $classNames, colspan: $colSpan, columnFormat: $colFormats, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), delimiter: $delimiter, foreignKeys: $foreignKeys, header: $headerRow, hiddenAdditional: $hiddenAdditional, hiddenId: $hiddenId, hideColumnIndexes: $hideColIndexes, html: $html, id: NULL, link: $link, note: $showNote, selectedRow: $selectedColumnVals, suffix: str_replace(" ", "", ucwords($title)), width: $width);
    $outputTable = $htmlTable->getHtml(results: $result, resultHeaders: $resultHeaders);
    if (REPORT_ID_TOURNAMENT_RESULTS == $reportId && $outputTable == "") {
        $output .= "<br>No results because not yet entered/played";
    } else {
        $output .= $outputTable;
    }
    $hiddenReportId = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: array(REPORT_ID_FIELD_NAME . "2"), cols: NULL, disabled: false, id: REPORT_ID_FIELD_NAME, maxLength: NULL, name: REPORT_ID_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $reportId, wrap: NULL);
    $output .= $hiddenReportId->getHtml();
    $hiddenMode = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::FIELD_NAME_MODE, maxLength: NULL, name: Constant::FIELD_NAME_MODE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $mode, wrap: NULL);
    $output .= $hiddenMode->getHtml();
    $output .= "</div>\n";
    $outputDialog = "";
    if ($reportId == REPORT_ID_FEES) {
        $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getFeeDetail(indexed: true);
        $resultHeaders = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getFeeDetail(indexed: false);
        $colFormats = array(array(4, "currency", 0), array(5, "currency", 0), array(6, "currency", 0));
        $hideColIndexes = NULL;
        $htmlTable = new HtmlTable(caption: NULL, class: NULL, colspan: NULL, columnFormat: $colFormats, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), delimiter: $delimiter, foreignKeys: NULL, header: true, hiddenAdditional: $hiddenAdditional, hiddenId: NULL, hideColumnIndexes: $hideColIndexes, html: NULL, id: NULL, link: NULL, note: true, selectedRow: NULL, suffix: "FeeDetail", width: "100%");
        $outputTemp = $htmlTable->getHtml(results: $result, resultHeaders: $resultHeaders);
        if (0 < count($result)) {
            $outputDialog =
                "<script type=\"module\">\n" .
                "  import { reportsInputLocal } from \"./scripts/reports.js\";\n" .
    //             "  document.querySelector(\"#fee_detail_link\").addEventListener(\"click\", (evt) => reportsInputLocal.showFeeDetail());\n" .
                "  document.querySelectorAll(\"a[id^='fee_detail_link']\").forEach(link => {\n" .
                "    let id = link.id.split(\"_\");\n" .
                "    let qs = link.href.split(\"?\");\n" .
                "    let values = qs[qs.length - 1].split(\"=\");\n" .
                "    link.addEventListener(\"click\", (evt) => reportsInputLocal.showFeeDetail(values[1]));\n" .
                "  });\n" .
                "</script>\n";
            $outputDialog .=
                "<dialog class=\"dialog\" id=\"dialogFeeDetail\">\n" .
                " <form method=\"dialog\">\n" .
                "  <header>\n" .
                "   <h2>Fee Detail<span id=\"dialogFeeDetailSpan\"></span></h2>\n" .
                "   <button class=\"dialogButton\" id=\"dialogFeeDetail-header--cancel-btn\">X</button>\n" .
                "  </header>\n" .
                "  <main>\n" .
            $outputTemp .
                "  </main>\n" .
                " </form>\n" .
                "</dialog>\n";
        }
    }
}
$smarty->assign("content", $output);
$smarty->assign("contentDialog", $outputDialog);
$smarty->display("reports.tpl");