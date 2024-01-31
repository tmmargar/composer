<?php
declare(strict_types = 1);
namespace ccp;
use Poker\Ccp\classes\model\Constant;
require_once "init.php";
$output = "";
$entityManager = getEntityManager();
$resultList = $entityManager->getRepository(Constant::ENTITY_SEASONS)->getActives();
$resultList = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getSeasonStats(startDate: $resultList->getSeasonStartDate(), endDate: $resultList->getSeasonEndDate());
if (0 < count(value: $resultList)) {
//   print_r($resultList);die();
  $count = 0;
  $output .= " <table id=\"output\">\n";
  foreach ($resultList as $result) {
//     print_r($result);
//     if ($count == 0) {
//       $output .= "  <thead>\n";
//     } else if ($count == 1) {
      $output .= "  <tbody>\n";
//     }
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