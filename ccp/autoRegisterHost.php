<?php
declare(strict_types = 1);
namespace ccp;
use Poker\Ccp\classes\model\Address;
use Poker\Ccp\classes\model\Constant;
use Poker\Ccp\classes\model\DateTime;
use Poker\Ccp\classes\model\Email;
use Poker\Ccp\classes\utility\SessionUtility;
require_once "init.php";
$now = new DateTime(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, time: "now");
$output .=
  "<script type=\"module\">\n" .
  "  import { dataTable, display, input } from \"./scripts/import.js\";\n" .
  "  let aryMessages = [];\n";
$output .= isset($mode) ? "  aryMessages.push(\"###Run at " . $now->getDisplayLongTimeFormat() . "###\");\n" : "\r";
$dateTime = new DateTime(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, time: "now");
$params = array($dateTime->getDatabaseFormat(), $dateTime->getDatabaseFormat());
$resultList = $databaseResult->getAutoRegisterHost(params: $params);
if (count($resultList) == 0) {
  $output .= isset($mode) ? "  aryMessages.push(\"No tournaments needing auto host registration or host is already registered\");\n" : "\r";
} else {
  foreach ($resultList as $tournament) {
    $player = $tournament->getLocation()->getPlayer();
    $params = array((int) $tournament->getId(), (int) $player->getId(), "N/A");
    $rowCount = $databaseResult->insertRegistration(params: $params);
    if (1 == $rowCount) {
      $output .= isset($mode) ? "  aryMessages.push(\"Successfully registered " . $player->getName() . " for tournament on " . $tournament->getDate()->getDisplayFormat() . " starting at " . $tournament->getStartTime()->getDisplayAmPmFormat() . "\");\n" : "\r";
    }
    $tournamentAddress = $player->getAddress();
    $email = new Email(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), fromName: array(Constant::NAME_STAFF), fromEmail: array(Constant::EMAIL_STAFF()), toName: array($player->getName()), toEmail: array($player->getEmail()), ccName: NULL, ccEmail: NULL, bccName: NULL, bccEmail: NULL, subject: NULL, body: NULL);
    $emailAddress = new Address(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, address: $tournamentAddress->getAddress(), city: $tournamentAddress->getCity(), state: $tournamentAddress->getState(), zip: $tournamentAddress->getZip());
    $output .= (isset($mode) ? "  aryMessages.push(\"" . $email->sendRegisteredEmail(address: $emailAddress, tournament: $tournament, feeStatus: "Paid", waitList: 0) . "\");\n" : "\r");
    $email = new Email(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), fromName: array(Constant::NAME_STAFF), fromEmail: array(Constant::EMAIL_STAFF()), toName: array(Constant::NAME_STAFF), toEmail: array(Constant::EMAIL_STAFF()), ccName: NULL, ccEmail: NULL, bccName: NULL, bccEmail: NULL, subject: NULL, body: NULL);
    $emailAddress = new Address(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, address: $tournamentAddress->getAddress(), city: $tournamentAddress->getCity(), state: $tournamentAddress->getState(), zip: $tournamentAddress->getZip());
    $output .= isset($mode) ? "  aryMessages.push(\"" . $email->sendRegisteredEmail(address: $emailAddress, tournament: $tournament, feeStatus: "Paid", waitList: 0, autoRegister: $player->getName()) . "\");\n" : "\r";
  }
}
$output .= "  if (aryMessages.length > 0) {display.showMessages({messages: aryMessages});}\n</script>\n";
if (isset($_GET[Constant::FIELD_NAME_MODE])) {
  $smarty->assign("title", "Chip Chair and a Prayer Auto Register Host");
  $smarty->assign("heading", "Auto Register Host");
  $smarty->assign("mode", Constant::MODE_VIEW);
  $smarty->assign("action", $_SERVER["SCRIPT_NAME"]);
  $smarty->assign("formName", "frmAutoRegisterHost");
  $smarty->assign("content", $output);
  $smarty->display("base.tpl");
} else {
  echo $output;
}