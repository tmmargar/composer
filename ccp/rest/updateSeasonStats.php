<?php
declare(strict_types = 1);
namespace ccp;
use Poker\Ccp\classes\model\Constant;
require_once "init.php";
$output = "";
$params = array(Constant::FLAG_YES_DATABASE);
$resultList = $databaseResult->getSeasonByActive(params: $params);
$season = $resultList[0];
$params = array($season->getStartDate()->getDatabaseFormat(), $season->getEndDate()->getDatabaseFormat());
//$params = array("2022-01-01", "2022-11-30");
$resultList = $databaseResult->getSeasonStats(params: $params);
if (0 < count(value: $resultList)) {
//   print_r($resultList);die();
  $count = 0;
  $output .= " <table id=\"output\">\n";
  foreach ($resultList as $result) {
//     print_r($result);
    if ($count == 0) {
      $output .= "  <thead>\n";
    } else if ($count == 1) {
      $output .= "  <tbody>\n";
    }
    $output .= "  <tr>\n";
//     $output .= "   <td>" . $result[0] . " " . $result[1] . "</td>\n";
//     $output .= "   <td>" . $result[2] . "</td>\n";
    foreach ($result as $value) {
      $output .= "   <td>" . $value . "</td>\n";
    }
    $output .= "  </tr>\n";
    if ($count == 0) {
      $output .= "  </thead>\n";
    }
    $count++;
  }
  $output .= "</tbody>\n</table>\n";
} else {
  $output .= "  None\n";
}
echo $output;