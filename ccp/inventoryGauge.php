<?php
declare(strict_types = 1);
namespace ccp;
use Poker\Ccp\Model\Constant;
use Poker\Ccp\Utility\SessionUtility;
require_once "init.php";
$smarty->assign("title", "Chip Chair and a Prayer Inventory");
$smarty->assign("heading", "Inventory");
$output = " <script src=\"https://www.gstatic.com/charts/loader.js\"></script>\n";
$resultList = $entityManager->getRepository(Constant::ENTITY_INVENTORIES)->getById(inventoryId: NULL);
$ctr = 0;
while ($ctr < count($resultList)) {
    $output .= "   <div class=\"center title\" style=\"float: left; width: 250px;\">" . $resultList[$ctr]->getInventoryTypes()->getInventoryTypeName() . "</div>\n";
    $ctr++;
}
$output .= "    <div class=\"clear\"></div>\n";
$ctr = 0;
while ($ctr < count($resultList)) {
    $name = $resultList[$ctr]->getInventoryTypes()->getInventoryTypeName();
    $nameCode = str_replace(" ", "", $name);
    $currentAmount = $resultList[$ctr]->getInventoryCurrentAmount();
    $greenFrom = $resultList[$ctr]->getInventoryWarningAmount();
    $greenTo = $resultList[$ctr]->getInventoryTypes()->getInventoryTypeMaxAmount();
    $yellowFrom = $resultList[$ctr]->getInventoryOrderAmount();
    $yellowTo = $greenFrom;
    $redFrom = $resultList[$ctr]->getInventoryTypes()->getInventoryTypeMinAmount();
    $redTo = $resultList[$ctr]->getInventoryOrderAmount();
    $labelIncrement = ($resultList[$ctr]->getInventoryTypes()->getInventoryTypeMaxAmount() - $resultList[$ctr]->getInventoryTypes()->getInventoryTypeMinAmount()) / 4;
    $label1 = $resultList[$ctr]->getInventoryTypes()->getInventoryTypeMinAmount() + $labelIncrement;
    $label2 = $label1 + $labelIncrement;
    $label3 = $label2 + $labelIncrement;
    $label4 = $resultList[$ctr]->getInventoryTypes()->getInventoryTypeMaxAmount();
    $output .= " <script>\n";
    $output .= "  google.charts.load('current', {'packages':['gauge']});\n";
    $output .= "  google.charts.setOnLoadCallback(drawGauge);\n";
    $output .= "  var gaugeOptions" . $nameCode . " = {min: " . $resultList[$ctr]->getInventoryTypes()->getInventoryTypeMinAmount() . ", max: " . $resultList[$ctr]->getInventoryTypes()->getInventoryTypeMaxAmount() . ", yellowFrom: " . $yellowFrom . ", yellowTo: " . $yellowTo . ", redFrom: " . $redFrom . ", redTo: " . $redTo . ", majorTicks: [\"" . $resultList[$ctr]->getInventoryTypes()->getInventoryTypeMinAmount() . "\", \"$label1\", \"$label2\", \"$label3\", \"$label4\"], minorTicks: 5, greenFrom: " . $greenFrom .", greenTo: " . $greenTo . ", width: 700};\n";
    $output .= "  function drawGauge() {\n";
    $output .= "   gaugeData" . $nameCode . " = new google.visualization.DataTable();\n";
    $output .= "   gaugeData" . $nameCode . ".addColumn('number');\n";
    $output .= "   gaugeData" . $nameCode . ".addRows(1);\n";
    $output .= "   gaugeData" . $nameCode . ".setCell(0, 0, " . $currentAmount . ");\n";
    $output .= "   var gauge" . $nameCode . " = new google.visualization.Gauge(document.getElementById('gaugeInventory" . $nameCode . "'));\n";
    $output .= "   gauge" . $nameCode . ".draw(gaugeData" . $nameCode . ", gaugeOptions" . $nameCode . ");\n";
    $output .= "  }\n";
    $output .= "$(window).on(\"resize\", function(event) {\n";
    $output .= "  drawGauge();\n";
    $output .= "});\n";
    $output .= " </script>\n";
    $output .= "   <div class=\"center\" id=\"gaugeInventory" . $nameCode . "\" style=\"float: left; height: 250px; width: fit-content;\"></div>\n";
    $ctr++;
}
$smarty->assign("content", $output);
$smarty->display("inventory.tpl");