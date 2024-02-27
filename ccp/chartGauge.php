<?php
declare(strict_types = 1);
namespace ccp;
use Poker\Ccp\Model\Constant;
use Poker\Ccp\Utility\SessionUtility;
require_once "init.php";
if (!defined("REPORT_ID_PARAM_NAME")) {define("PLAYER_ID_PARAM_NAME", "playerId");}
$playerId = (int) ((isset($_POST[PLAYER_ID_PARAM_NAME]) ? $_POST[PLAYER_ID_PARAM_NAME] : isset($_GET[PLAYER_ID_PARAM_NAME])) ? $_GET[PLAYER_ID_PARAM_NAME] : SessionUtility::getValue("userid"));
$output = "";
$output .= " <script src=\"https://www.gstatic.com/charts/loader.js\"></script>\n";
$startDate = SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_START_DATE);
$endDate = SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_END_DATE);
$resultList = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getCountForDates(startDate: $startDate, endDate: $endDate);
if (0 < count($resultList)) {
    $tournamentsTotal = count($resultList) == 0 ? 1 : count($resultList);
    $labelIncrement = $tournamentsTotal / 4;
    $labelPlayed1 = $labelIncrement;
    $labelPlayed2 = $labelIncrement * 2;
    $labelPlayed3 = $labelIncrement * 3;
    $labelPlayed4 = $tournamentsTotal;
}
$resultList2 = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getCountForUserAndDates(playerId: $playerId, startDate: $startDate, endDate: $endDate);
if (0 < count($resultList2)) {
    $tournamentsPlayed = count($resultList2);
} else {
    $tournamentsPlayed = 0;
}
if (0 < count(value: $resultList)) {
    $tournamentsLeft = count($resultList) == 0 ? 1 : count($resultList);
    $yellowFrom = 0;
    $yellowTo = $tournamentsLeft / 2;
    $redFrom = $yellowTo;
    $redTo = $tournamentsLeft;
    $labelIncrement = $tournamentsLeft / 4;
    $labelNeed1 = $labelIncrement;
    $labelNeed2 = $labelIncrement * 2;
    $labelNeed3 = $labelIncrement * 3;
    $labelNeed4 = $tournamentsLeft;
}
$output .= " <script>\n";
$output .= "  google.charts.load('current', {'packages':['gauge']});\n";
$output .= "  google.charts.setOnLoadCallback(drawGauge);\n";
// $output .= " var gaugeOptions = {min: 0, max: 20, yellowFrom: 5, yellowTo: 10, redFrom: 0, redTo: 5, majorTicks: [\"0\", \"5\", \"10\", \"15\", \"20\"], minorTicks: 5, greenFrom: 10, greenTo: 20};\n";
// $output .= "  var gaugeOptions = {min: 0, max: $tournamentsTotal, yellowFrom: 5, yellowTo: 10, redFrom: 0, redTo: 5, majorTicks: [\"0\", \"$labelPlayed1\", \"$labelPlayed2\", \"$labelPlayed3\", \"$labelPlayed4\"], minorTicks: 5, greenFrom: 10, greenTo: $tournamentsTotal};\n";
$qualifyLow = SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_CHAMPIONSHIP_QUALIFY) / 2;
$output .= "  var gaugeOptions = {min: 0, max: $tournamentsTotal, yellowFrom: " . $qualifyLow . ", yellowTo: " . SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_CHAMPIONSHIP_QUALIFY) . ", redFrom: 0, redTo: " . $qualifyLow . ", majorTicks: [\"0\", \"$labelPlayed1\", \"$labelPlayed2\", \"$labelPlayed3\", \"$labelPlayed4\"], minorTicks: 5, greenFrom: " . SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_CHAMPIONSHIP_QUALIFY) .", greenTo: $tournamentsTotal, width: 700};\n";
// $output .= " var gaugeOptions2 = {min: 0, max: $tournamentsLeft, yellowFrom: 0, yellowTo: 4, redFrom: 4, redTo: $tournamentsLeft, majorTicks: [\"0\", \"2\", \"4\", \"6\", \"8\"], minorTicks: 2};\n";
$output .= "  var gaugeOptions2 = {min: 0, max: $tournamentsLeft, yellowFrom: $yellowFrom, yellowTo: $yellowTo, redFrom: $redFrom, redTo: $redTo, majorTicks: [\"0\", \"$labelNeed1\", \"$labelNeed2\", \"$labelNeed3\", \"$labelNeed4\"], minorTicks: 2, width: 700};\n";
//$output .= "  var gaugeOptions2 = {min: 0, max: 4, yellowFrom: 0, yellowTo: 0, redFrom: 0, redTo: 0, majorTicks: [\"0\", \"0\", \"2\", \"3\", \"4\"], minorTicks: 2};\n";
$output .= "  function drawGauge() {\n";
$output .= "   gaugeData = new google.visualization.DataTable();\n";
$output .= "   gaugeData.addColumn('number', 'Play');\n";
$output .= "   gaugeData.addRows(1);\n";
$output .= "   gaugeData.setCell(0, 0, $tournamentsPlayed);\n";
$output .= "   var gauge = new google.visualization.Gauge(document.getElementById('gaugePlayed'));\n";
$output .= "   gauge.draw(gaugeData, gaugeOptions);\n";
// $output .= "   // make table width 100% so it centers\n";
// $output .= "   $(\"#gaugePlayed\").find(\"table\").css(\"width\", \"100%\");\n";
$output .= "   gaugeData2 = new google.visualization.DataTable();\n";
$output .= "   gaugeData2.addColumn('number', 'Need');\n";
$output .= "   gaugeData2.addRows(1);\n";
$output .= "   gaugeData2.setCell(0, 0, (" . SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_CHAMPIONSHIP_QUALIFY) . " - $tournamentsPlayed > 0 ? " . SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_CHAMPIONSHIP_QUALIFY) . " - $tournamentsPlayed : 0));\n";
$output .= "   var gauge2 = new google.visualization.Gauge(document.getElementById('gaugeNeed'));\n";
$output .= "   gauge2.draw(gaugeData2, gaugeOptions2);\n";
// $output .= "   // make table width 100% so it centers\n";
// $output .= "   $(\"#gaugeNeed\").find(\"table\").css(\"width\", \"100%\");\n";
$output .= "  }\n";
$output .= "$(window).on(\"resize\", function(event) {\n";
$output .= "  drawGauge();\n";
$output .= "});\n";
$output .= " </script>\n";
$output .= "   <div class=\"title\">Championship Qualification</div>\n";
$output .= "   <div class=\"center\" id=\"gaugeNeed\" style=\"height: 250px; width: fit-content;\"></div>\n";
$output .= "   <div class=\"center\" id=\"gaugePlayed\" style=\"height: 250px; width: fit-content;\"></div>\n";
return $output;