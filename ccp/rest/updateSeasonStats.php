<?php
declare(strict_types = 1);
namespace ccp;
use Poker\Ccp\Model\Constant;
require_once "init.php";
$output = "";
$entityManager = getEntityManager();
$resultList = $entityManager->getRepository(entityName: Constant::ENTITY_SEASONS)->getActives();
$resultList = $entityManager->getRepository(entityName: Constant::ENTITY_RESULTS)->getSeasonStats(startDate: $resultList->getSeasonStartDate(), endDate: $resultList->getSeasonEndDate());
if (0 < count(value: $resultList)) {
    $count = 0;
    $output .= " <table id=\"output\">\n";
    foreach ($resultList as $result) {
        $output .= "  <tbody>\n";
        $output .= "  <tr>\n";
        foreach ($result as $value) {
            $output .= "   <td>" . $value . "</td>\n";
        }
        $output .= "  </tr>\n";
        $count++;
    }
    $output .= "</tbody>\n</table>\n";
} else {
    $output .= "  None\n";
}
echo $output;