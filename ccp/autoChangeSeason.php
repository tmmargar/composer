<?php
declare(strict_types = 1);
namespace ccp;
use DateTime;
use Exception;
use Poker\Ccp\Model\Address;
use Poker\Ccp\Model\Constant;
use Poker\Ccp\Model\Email;
use Poker\Ccp\Model\Tournament;
use Poker\Ccp\Utility\DateTimeUtility;
use Poker\Ccp\Utility\SessionUtility;
use Poker\Ccp\Entity\Results;
require_once "init.php";
$now = new DateTime();
$output .=
    "<script type=\"module\">\n" .
    "  import { dataTable, display, input } from \"./scripts/import.js\";\n" .
    "  let aryMessages = [];\n";
$output .= isset($mode) ? "  aryMessages.push(\"###Run at " . DateTimeUtility::formatDisplayLong(value: $now) . "###\");\n" : "\r";
$dateTime = new DateTime();
$resultList = $entityManager->getRepository(entityName: Constant::ENTITY_SEASONS)->getActives();
if (NULL === $resultList) {
    $output .= isset($mode) ? "  aryMessages.push(\"No active seasons\");\n" : "\r";
} else {
    $seCurrent = $resultList;
    $maxId = $entityManager->getRepository(entityName: Constant::ENTITY_SEASONS)->getMaxId()[1];
    $resultList = $entityManager->getRepository(entityName: Constant::ENTITY_SEASONS)->getById(seasonId: $maxId);
    $seNew = $resultList[0];
    $seCurrent->setSeasonActiveFlag("0");
    $entityManager->persist(entity: $seCurrent);
    $seNew->setSeasonActiveFlag("1");
    $entityManager->persist(entity: $seNew);
    try {
        $entityManager->flush();
        SessionUtility::destroyAllSessions();
    } catch (Exception $e) {
        $errors = $e->getMessage();
    }
    if (isset($errors)) {
        $output .= "  display.showErrors({errors: [ \"" . $errors . "\" ]});\n";
    } else {
        $output .= isset($mode) ? "  aryMessages.push(\"Changed active season to " . $seNew->getSeasonId() . " from " . DateTimeUtility::formatDisplayDate(value: $seNew->getseasonStartDate()) . " to " . DateTimeUtility::formatDisplayDate(value: $seNew->getseasonEndDate()) ."\");\n" : "\r";
    }
}
$output .= "  if (aryMessages.length > 0) {display.showMessages({messages: aryMessages});}\n</script>\n";
if (isset($_GET[Constant::FIELD_NAME_MODE])) {
    $smarty->assign("title", "Chip Chair and a Prayer Auto Change Active Season");
    $smarty->assign("heading", "Auto Change Active Season");
    $smarty->assign("mode", Constant::MODE_VIEW);
    $smarty->assign("action", $_SERVER["SCRIPT_NAME"]);
    $smarty->assign("formName", "frmAutoChangeSeason");
    $smarty->assign("content", $output);
    $smarty->display("base.tpl");
} else {
    echo $output;
}