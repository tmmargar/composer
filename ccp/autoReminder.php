<?php
declare(strict_types = 1);
namespace ccp;
use Poker\Ccp\classes\model\Address;
use Poker\Ccp\classes\model\Constant;
use Poker\Ccp\classes\model\DatabaseResult;
use Poker\Ccp\classes\model\DateTime;
use Poker\Ccp\classes\model\Email;
use Poker\Ccp\classes\utility\SessionUtility;
require_once "init.php";
if (Constant::FLAG_LOCAL()) {
  set_time_limit(240);
}
$now = new DateTime(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, time: "now");
$output .=
  "<script type=\"module\">\n" .
  "  import { dataTable, display, input } from \"./scripts/import.js\";\n" .
  "  let aryMessages = [];\n";
$output .= isset($mode) ? "  aryMessages.push(\"###Run at " . $now->getDisplayLongTimeFormat() . "###\");\n" : "\r";
$days = 14;
$params = array((int) $days);
$resultList = $databaseResult->getPlayersForEmailNotifications(params: $params);
$resultList2 = $databaseResult->getTournamentsForEmailNotifications(params: $params);
if (count($resultList2) == 0) {
  $output .= isset($mode) ? "  aryMessages.push(\"No tournaments needing auto email notification for registration open (" . $days . " days before)\");\n" : "\r";
} else {
  foreach ($resultList as $player) {
    foreach ($resultList2 as $tournament) {
      $dt = new \DateTime();
      // echo "<br>" . $player->getName() . " -- " . $dt->getTimestamp();
      $locationPlayer = $tournament->getLocation()->getPlayer();
      $tournamentAddress = $locationPlayer->getAddress();
      $email = new Email(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), fromName: array(Constant::NAME_STAFF), fromEmail: array(Constant::EMAIL_STAFF()), toName: array($player->getName()), toEmail: array($player->getEmail()), ccName: NULL, ccEmail: NULL, bccName: NULL, bccEmail: NULL, subject: NULL, body: NULL);
      $emailAddress = new Address(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, address: $tournamentAddress->getAddress(), city: $tournamentAddress->getCity(), state: $tournamentAddress->getState(), zip: $tournamentAddress->getZip());
      $output .= isset($mode) ? "  aryMessages.push(\"" . $email->sendReminderEmail(address: $emailAddress, tournament: $tournament, waitListCount: 0) . "\");\n" : "\r";
    }
  }
}
$days = 7;
$params = array((int) $days);
$resultList = $databaseResult->getPlayersForEmailNotifications(params: $params);
$resultList2 = $databaseResult->getTournamentsForEmailNotifications(params: $params);
if (count($resultList2) == 0) {
  $output .= isset($mode) ? "  aryMessages.push(\"No tournaments needing auto email notification for registration open (" . $days . " days before)\");\n" : "\r";
} else {
  foreach ($resultList as $player) {
    foreach ($resultList2 as $tournament) {
      $dt = new \DateTime();
      // echo "<br>" . $player->getName() . " -- " . $dt->getTimestamp();
      $locationPlayer = $tournament->getLocation()->getPlayer();
      $tournamentAddress = $locationPlayer->getAddress();
      $email = new Email(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), fromName: array(Constant::NAME_STAFF), fromEmail: array(Constant::EMAIL_STAFF()), toName: array($player->getName()), toEmail: array($player->getEmail()), ccName: NULL, ccEmail: NULL, bccName: NULL, bccEmail: NULL, subject: NULL, body: NULL);
      $emailAddress = new Address(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, address: $tournamentAddress->getAddress(), city: $tournamentAddress->getCity(), state: $tournamentAddress->getState(), zip: $tournamentAddress->getZip());
      $output .= isset($mode) ? "  aryMessages.push(\"" . $email->sendReminderEmail(address: $emailAddress, tournament: $tournament, waitListCount: 0) . "\");\n" : "\r";
    }
  }
}
$days = 2;
$params = array((int) $days);
$resultList = $databaseResult->getPlayersForEmailNotifications(params: $params);
$resultList2 = $databaseResult->getTournamentsForEmailNotifications(params: $params);
if (count($resultList2) == 0) {
  $output .= isset($mode) ? "  aryMessages.push(\"No tournaments needing auto email notification for registration open (" . $days . " days before)\");\n" : "\r";
} else {
  foreach ($resultList as $player) {
    foreach ($resultList2 as $tournament) {
      $locationPlayer = $tournament->getLocation()->getPlayer();
      $tournamentAddress = $locationPlayer->getAddress();
      $email = new Email(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), fromName: array(Constant::NAME_STAFF), fromEmail: array(Constant::EMAIL_STAFF()), toName: array($player->getName()), toEmail: array($player->getEmail()), ccName: NULL, ccEmail: NULL, bccName: NULL, bccEmail: NULL, subject: NULL, body: NULL);
      $emailAddress = new Address(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, address: $tournamentAddress->getAddress(), city: $tournamentAddress->getCity(), state: $tournamentAddress->getState(), zip: $tournamentAddress->getZip());
      $output .= isset($mode) ? "  aryMessages.push(\"" . $email->sendReminderEmail(address: $emailAddress, tournament: $tournament, waitListCount: 0) . "\");\n" : "\r";
    }
  }
}
$output .= "  if (aryMessages.length > 0) {display.showMessages({messages: aryMessages});}\n</script>\n";
if (isset($_GET[Constant::FIELD_NAME_MODE])) {
  $smarty->assign("title", "Chip Chair and a Prayer Auto Reminder");
  $smarty->assign("heading", "Auto Reminder");
  $smarty->assign("mode", Constant::MODE_VIEW);
  $smarty->assign("action", $_SERVER["SCRIPT_NAME"]);
  $smarty->assign("formName", "frmAutoReminder");
  $smarty->assign("content", $output);
  $smarty->display("base.tpl");
} else {
  echo $output;
}