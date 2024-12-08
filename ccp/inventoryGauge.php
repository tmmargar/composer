<?php
declare(strict_types = 1);
namespace ccp;
use Poker\Ccp\Model\Constant;
use Poker\Ccp\Utility\SessionUtility;
require_once "init.php";
$smarty->assign("title", "Chip Chair and a Prayer Inventory");
$smarty->assign("heading", "Inventory");
$output = " <script src=\"https://www.gstatic.com/charts/loader.js\"></script>\n";
$output .= "   <div style=\"display: flex; flex-wrap:wrap\">\n";
$resultList = $entityManager->getRepository(entityName: Constant::ENTITY_INVENTORIES)->getById(inventoryId: NULL);
$countLoop = 4;
$ctr = 0;
$ctr2 = 0;
$ctr2Loop = $countLoop;
while ($ctr < count(value: $resultList)) {
    while ($ctr2 < $ctr2Loop) {
        $output .= "   <div class=\"center title\" style=\"width: 25%;\">" . ($ctr2 < count(value: $resultList) ? $resultList[$ctr2]->getInventoryTypes()->getInventoryTypeName() : "") . "</div>\n";
        $ctr2++;
    }
    $ctr2 = $ctr;
    while ($ctr2 < $ctr2Loop) {
        if ($ctr2 < count(value: $resultList)) {
            $name = $resultList[$ctr2]->getInventoryTypes()->getInventoryTypeName();
            $nameCode = str_replace(search: " ", replace: "", subject: $name);
            $currentAmount = $resultList[$ctr2]->getInventoryCurrentAmount();
            $greenFrom = $resultList[$ctr2]->getInventoryWarningAmount();
            $greenTo = $resultList[$ctr2]->getInventoryTypes()->getInventoryTypeMaxAmount();
            $yellowFrom = $resultList[$ctr2]->getInventoryOrderAmount();
            $yellowTo = $greenFrom;
            $redFrom = $resultList[$ctr2]->getInventoryTypes()->getInventoryTypeMinAmount();
            $redTo = $resultList[$ctr2]->getInventoryOrderAmount();
            $labelIncrement = ($resultList[$ctr2]->getInventoryTypes()->getInventoryTypeMaxAmount() - $resultList[$ctr2]->getInventoryTypes()->getInventoryTypeMinAmount()) / 4;
            $label1 = $resultList[$ctr2]->getInventoryTypes()->getInventoryTypeMinAmount() + $labelIncrement;
            $label2 = $label1 + $labelIncrement;
            $label3 = $label2 + $labelIncrement;
            $label4 = $resultList[$ctr2]->getInventoryTypes()->getInventoryTypeMaxAmount();
            $output .= " <script>\n";
            $output .= "  google.charts.load('current', {'packages':['gauge']});\n";
            $output .= "  google.charts.setOnLoadCallback(drawGauge);\n";
            $output .= "  var gaugeOptions" . $nameCode . " = {min: " . $resultList[$ctr2]->getInventoryTypes()->getInventoryTypeMinAmount() . ", max: " . $resultList[$ctr2]->getInventoryTypes()->getInventoryTypeMaxAmount() . ", yellowFrom: " . $yellowFrom . ", yellowTo: " . $yellowTo . ", redFrom: " . $redFrom . ", redTo: " . $redTo . ", majorTicks: [\"" . $resultList[$ctr2]->getInventoryTypes()->getInventoryTypeMinAmount() . "\", \"$label1\", \"$label2\", \"$label3\", \"$label4\"], minorTicks: 5, greenFrom: " . $greenFrom .", greenTo: " . $greenTo . ", width: 700};\n";
            $output .= "  function drawGauge() {\n";
            $output .= "   gaugeData" . $nameCode . " = new google.visualization.DataTable();\n";
            $output .= "   gaugeData" . $nameCode . ".addColumn('number');\n";
            $output .= "   gaugeData" . $nameCode . ".addRows(1);\n";
            $output .= "   gaugeData" . $nameCode . ".setCell(0, 0, " . $currentAmount . ");\n";
            $output .= "   var gauge" . $nameCode . " = new google.visualization.Gauge(document.getElementById('gaugeInventory" . $nameCode . "'));\n";
            $output .= "   gauge" . $nameCode . ".draw(gaugeData" . $nameCode . ", gaugeOptions" . $nameCode . ");\n";
            $output .= "  }\n";
            $output .= " </script>\n";
            $output .= "<script type=\"module\">\n";
            $output .= "import \"./vendor/components/jquery/jquery.js\";\n";
            $output .= "$(window).on(\"resize\", function(event) {\n";
            $output .= "  drawGauge();\n";
            $output .= "});\n";
            $output .= " </script>\n";
            $output .= "   <div class=\"center\" id=\"gaugeInventory" . $nameCode . "\" style=\"height: 250px; margin: auto; width: 25%;\"></div>\n";
        } else {
            $output .= "   <div class=\"center\" id=\"gaugeInventory\" style=\"height: 250px; margin: auto; width: 25%;\"></div>\n";
        }
        $ctr2++;
    }
    $ctr = $ctr2;
    $ctr2Loop = $ctr2Loop + $countLoop;
}
$output .= "</div>\n";
$smarty->assign("content", $output);
$smarty->display("inventory.tpl");